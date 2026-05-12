<?php

namespace App\Jobs;

use App\Models\Audit;
use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class ProcessAuditJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 300;
    public $tries = 5;

    /**
     * Backoff strategy: wait 65s on first retry (Gemini free tier resets per minute),
     * then progressively longer for subsequent retries.
     */
    public function backoff(): array
    {
        return [65, 120, 180, 300];
    }

    public function __construct(
        private int $projectId,
        private string $commitHash,
        private string $diff,
    ) {}

    public function handle(): void
    {
        $project = Project::find($this->projectId);

        if (!$project) {
            return;
        }

        $audit = Audit::create([
            'project_id'  => $this->projectId,
            'commit_hash' => $this->commitHash,
            'status'      => 'pending',
        ]);

        try {
            $result = $this->callAIEngine($project, $this->commitHash);

            $audit->update([
                'score'       => $result['score'] ?? 0,
                'status'      => $result['status'] ?? 'failed',
                'result_json' => $result,
            ]);
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();

            // If rate limited, mark pending and re-throw so queue retries with backoff
            if (str_contains($errorMsg, 'rate limit') || str_contains($errorMsg, 'quota')) {
                $audit->update([
                    'status'      => 'pending',
                    'result_json' => ['error' => 'Rate limited, retrying...'],
                ]);
                throw $e;
            }

            $audit->update([
                'status'      => 'failed',
                'result_json' => ['error' => $errorMsg],
            ]);
        }
    }

    /**
     * Parse GitHub repo URL to extract owner and repo name.
     */
    private function parseGitHubRepo(string $repoUrl): ?array
    {
        if (preg_match('#github\.com[/:]([^/]+)/([^/.]+?)(?:\.git)?$#', $repoUrl, $matches)) {
            return ['owner' => $matches[1], 'repo' => $matches[2]];
        }
        return null;
    }

    /**
     * Extract only node labels from a Mermaid flowchart spec.
     * Converts a 2000-char flowchart into a short list like "Features: Login Page, Register Page, ..."
     * This prevents Gemini from flagging repetitive Mermaid syntax as "looping content".
     */
    private function extractMermaidFeatures(string $specContent): string
    {
        if (empty(trim($specContent))) {
            return 'No spec provided.';
        }

        // Extract bracketed labels: A[Label text] or A([Label]) or A{Label}
        preg_match_all('/\w+[\[\(\{]([^\]\)\}]{3,50})[\]\)\}]/', $specContent, $matches);
        $labels = array_unique($matches[1] ?? []);

        // Filter out pure code/arrows
        $labels = array_filter($labels, function ($label) {
            return !preg_match('/^(true|false|yes|no|null|\d+)$/i', $label)
                && strlen(trim($label)) > 2;
        });

        // Extract subgraph names
        preg_match_all('/subgraph\s+\w+\s*\[?([^\]\n]+)\]?/', $specContent, $subMatches);
        $subgraphs = array_unique($subMatches[1] ?? []);

        $featureList = implode(', ', array_slice(array_values($labels), 0, 25));
        $flowList    = implode(', ', array_slice(array_values($subgraphs), 0, 5));

        $summary = "Features in flowchart: {$featureList}";
        if (!empty($flowList)) {
            $summary .= "\nFlow sections: {$flowList}";
        }

        return $summary ?: substr($specContent, 0, 500);
    }

    /**
     * Build headers for GitHub API calls.
     * Uses GITHUB_TOKEN if set (5000 req/hr), otherwise unauthenticated (60 req/hr).
     */
    private function githubHeaders(): array
    {
        $headers = ['User-Agent' => 'SpecGuardAI/1.0', 'Accept' => 'application/vnd.github+json'];
        $token = env('GITHUB_TOKEN', '');
        if (!empty($token)) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
        return $headers;
    }

    /**
     * Fetch full codebase snapshot at the given commit SHA via GitHub API.
     * Returns '__RATE_LIMITED__' if rate limited so caller can use diff fallback.
     */
    private function fetchCodebaseSnapshot(string $owner, string $repo, string $commitSha): string
    {
        $priorityPatterns = [
            'routes/web.php',
            'app/Http/Controllers/',
            'app/Http/Requests/',
            'app/Models/',
            'resources/views/',
        ];
        $ignoredPatterns = ['vendor/', 'node_modules/', 'storage/', 'public/', 'bootstrap/cache/'];
        $headers = $this->githubHeaders();

        try {
            $treeUrl = "https://api.github.com/repos/{$owner}/{$repo}/git/trees/{$commitSha}?recursive=1";
            $treeResponse = Http::timeout(15)->withHeaders($headers)->get($treeUrl);

            if (!$treeResponse->ok()) {
                $status = $treeResponse->status();
                $body   = $treeResponse->json();
                if ($status === 403 || $status === 429 || str_contains($body['message'] ?? '', 'rate limit')) {
                    return '__RATE_LIMITED__';
                }
                return "GitHub API error {$status}: " . ($body['message'] ?? $treeResponse->body());
            }

            $files = collect($treeResponse->json()['tree'] ?? [])
                ->filter(fn($item) => $item['type'] === 'blob')
                ->filter(function ($item) use ($ignoredPatterns) {
                    $path = $item['path'];
                    foreach ($ignoredPatterns as $ignored) {
                        if (str_starts_with($path, $ignored)) return false;
                    }
                    return (bool) preg_match('/\.(php|blade\.php)$/', $path);
                })
                ->sortByDesc(function ($item) use ($priorityPatterns) {
                    foreach ($priorityPatterns as $i => $pattern) {
                        if (str_starts_with($item['path'], $pattern)) return count($priorityPatterns) - $i;
                    }
                    return 0;
                })
                ->values();

            $codeSnapshot = '';
            $charCount    = 0;

            foreach ($files->take(5) as $file) {
                if ($charCount >= 3000) break;

                $blobUrl  = "https://api.github.com/repos/{$owner}/{$repo}/git/blobs/{$file['sha']}";
                $blobResp = Http::timeout(8)->withHeaders($headers)->get($blobUrl);

                if (!$blobResp->ok()) {
                    if (in_array($blobResp->status(), [403, 429])) break; // rate limited mid-way
                    continue;
                }

                $fileContent = base64_decode(str_replace(["\n", "\r"], '', $blobResp->json()['content'] ?? ''));
                if (empty(trim($fileContent))) continue;

                if (strlen($fileContent) > 500) {
                    $fileContent = substr($fileContent, 0, 500) . "\n// ...[truncated]";
                }

                $snippet = "\n--- {$file['path']} ---\n{$fileContent}\n";
                $codeSnapshot .= $snippet;
                $charCount += strlen($snippet);
            }

            return empty(trim($codeSnapshot))
                ? 'No relevant PHP/Blade implementation files found.'
                : $codeSnapshot;

        } catch (\Exception $e) {
            return 'Failed to fetch codebase: ' . $e->getMessage();
        }
    }

    /**
     * Fallback: fetch raw commit .diff when GitHub API is rate-limited.
     */
    private function fetchDiffFallback(string $commitHash, array $repoInfo): string
    {
        $owner   = $repoInfo['owner'];
        $repo    = $repoInfo['repo'];
        $diffUrl = "https://github.com/{$owner}/{$repo}/commit/{$commitHash}.diff";

        try {
            $response = Http::timeout(15)
                ->withHeaders(['User-Agent' => 'SpecGuardAI/1.0'])
                ->get($diffUrl);

            if (!$response->ok()) {
                return "Could not fetch diff fallback (HTTP {$response->status()}).";
            }

            $sections = preg_split('/(?=^diff --git )/m', $response->body(), -1, PREG_SPLIT_NO_EMPTY);
            $relevant = '';
            foreach ($sections as $section) {
                if (!preg_match('/\.(php|blade\.php)/', $section)) continue;
                if (preg_match('/(vendor|node_modules|composer\.lock)/', $section)) continue;
                $relevant .= substr($section, 0, 500) . "\n";
                if (strlen($relevant) >= 2500) break;
            }

            return empty(trim($relevant))
                ? 'Diff available but no PHP/Blade changes in this commit.'
                : "[NOTE: GitHub API rate-limited, using commit diff]\n" . $relevant;

        } catch (\Exception $e) {
            return 'Diff fetch failed: ' . $e->getMessage();
        }
    }

    private function callAIEngine(Project $project, string $commitHash): array
    {
        // Truncate PRD and spec to prevent Gemini "looping content" detection
        // (very long repetitive Mermaid specs trigger the safety filter)
        $prdContent  = substr($project->prd_content ?? 'No PRD provided.', 0, 1200);
        $specContent = $this->extractMermaidFeatures($project->spec_content ?? '');
        $repoInfo    = $this->parseGitHubRepo($project->repo_url ?? '');

        // 1. Try full codebase snapshot (most accurate)
        $codebaseContent = '';
        if ($repoInfo) {
            $snapshot = $this->fetchCodebaseSnapshot($repoInfo['owner'], $repoInfo['repo'], $commitHash);
            if ($snapshot === '__RATE_LIMITED__') {
                // 2. Fallback to commit diff
                $codebaseContent = $this->fetchDiffFallback($commitHash, $repoInfo);
            } else {
                $codebaseContent = $snapshot;
            }
        }

        if (empty(trim($codebaseContent))) {
            $codebaseContent = 'No code available (GitHub API unavailable).';
        }

        $systemPrompt = <<<PROMPT
You are SpecGuard AI — a compliance auditor. Your job is to compare a developer's CODEBASE against the project's PRD.

STEP 1 — Read the PRD carefully and identify ALL features/requirements mentioned.
STEP 2 — Read the CODEBASE and determine which features are implemented, partial, or missing.
STEP 3 — Output ONLY a valid JSON object. No markdown. No backticks. No explanation.

RULES:
- Feature keys must be short snake_case strings derived from the PRD (e.g. "user_registration", "product_search", "payment_gateway").
- Do NOT use fixed/hardcoded feature names. Derive them entirely from the PRD requirements.
- If a Controller, View, Route, or Model file exists matching a PRD feature → mark it implemented.
- If a feature is partially done (file exists but logic incomplete) → list in "partial".
- Missing = no relevant file/code found at all.
- Score: Start 0. +15 per implemented. +8 per partial. +5 bonus for auth middleware. -10 per PRD deviation.
- status = "complete" if score>=80, "partial" if 40-79, "failed" if <40. Max score 100.

OUTPUT (always output implemented and missing FIRST in case of truncation):
{"implemented":["feature_a","feature_b"],"missing":["feature_c"],"partial":["feature_d"],"score":75,"status":"partial","quality_notes":["max 3 concise notes"],"summary":"One sentence under 100 chars"}

Output ONLY the JSON. Nothing else.
PROMPT;


        $userMessage = <<<MSG
PRD:
{$prdContent}

MERMAID SPEC:
{$specContent}

CODEBASE AT COMMIT {$commitHash}:
{$codebaseContent}

Audit the codebase. Return ONLY the compact JSON.
MSG;

        try {
            $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
                'model'      => env('OPENAI_MODEL', 'gemini-2.5-flash'),
                'messages'   => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $userMessage],
                ],
                'max_tokens' => 1500,
            ]);

            $content = $response->choices[0]->message->content ?? '';
            $content = preg_replace('/^```(?:json)?\n?/m', '', $content);
            $content = preg_replace('/```$/m', '', $content);
            $content = trim($content);

            $result = json_decode($content, true);

            if (!is_array($result)) {
                // Regex fallback — extract implemented/missing arrays first
                preg_match('/"implemented"\s*:\s*(\[[^\]]*\])/', $content, $implMatch);
                preg_match('/"missing"\s*:\s*(\[[^\]]*\])/', $content, $missMatch);
                preg_match('/"score"\s*:\s*(\d+)/', $content, $scoreMatch);
                preg_match('/"status"\s*:\s*"([^"]+)"/', $content, $statusMatch);
                preg_match('/"summary"\s*:\s*"([^"]+)"/', $content, $summaryMatch);

                $implArray = [];
                $missArray = [];
                if (!empty($implMatch)) {
                    preg_match_all('/"([a-z_]+)"/', $implMatch[1], $m);
                    $implArray = $m[1] ?? [];
                }
                if (!empty($missMatch)) {
                    preg_match_all('/"([a-z_]+)"/', $missMatch[1], $m);
                    $missArray = $m[1] ?? [];
                }

                if (!empty($scoreMatch) || !empty($implArray)) {
                    $result = [
                        'implemented'          => $implArray,
                        'missing'              => $missArray,
                        'score'                => (int)($scoreMatch[1] ?? 0),
                        'status'               => $statusMatch[1] ?? 'failed',
                        'quality_notes'        => ['Response partially truncated.'],
                        'summary'              => $summaryMatch[1] ?? 'Partial audit.',
                        'node_status'          => [],
                        'missing_requirements' => [],
                    ];
                } else {
                    return [
                        'status'               => 'failed',
                        'score'                => 0,
                        'error'                => 'AI returned invalid JSON: ' . substr($content, 0, 300),
                        'node_status'          => [],
                        'implemented'          => [],
                        'missing'              => [],
                        'quality_notes'        => [],
                        'missing_requirements' => [],
                    ];
                }
            }

            // Build node_status dynamically from AI-returned features
            // by matching feature keywords against Mermaid spec node labels
            $implemented = $result['implemented'] ?? [];
            $partial     = $result['partial']     ?? [];
            $missing     = $result['missing']     ?? [];
            $allFeatures = array_unique(array_merge($implemented, $partial, $missing));
            $nodeStatus  = [];

            // Extract all word-like tokens from the Mermaid spec for matching
            $specText = $project->spec_content ?? '';
            preg_match_all('/\b([A-Za-z][A-Za-z0-9_]{2,})\b/', $specText, $nodeMatches);
            $specTokens = array_unique($nodeMatches[1] ?? []);

            foreach ($allFeatures as $feature) {
                $featureWords = array_filter(explode('_', strtolower($feature)), fn($w) => strlen($w) >= 4);
                $isImpl  = in_array($feature, $implemented);
                $isPart  = in_array($feature, $partial);
                $status  = $isImpl ? true : ($isPart ? 'partial' : false);

                // Match spec tokens containing any word from the feature key
                foreach ($specTokens as $token) {
                    $tokenLower = strtolower($token);
                    foreach ($featureWords as $word) {
                        if (str_contains($tokenLower, $word)) {
                            $nodeStatus[$token] = $status;
                            break;
                        }
                    }
                }
                // Always tag the raw feature key itself
                $nodeStatus[$feature] = $status;
            }


            $result['node_status']          = $nodeStatus;
            $result['missing_requirements'] = array_map(
                fn($f) => ucfirst(str_replace('_', ' ', $f)) . ' not found in codebase.',
                $missing
            );
            $result['status'] = $result['status'] ?? 'failed';
            $result['score']  = $result['score'] ?? 0;

            return $result;

        } catch (\Exception $e) {
            $msg = $e->getMessage();

            // Re-throw rate limit errors so handle() can retry with backoff
            if (str_contains(strtolower($msg), 'rate limit') ||
                str_contains(strtolower($msg), 'quota') ||
                str_contains(strtolower($msg), '429')) {
                throw $e;
            }

            return [
                'status'               => 'failed',
                'score'                => 0,
                'error'                => 'OpenAI call failed: ' . $msg,
                'node_status'          => [],
                'implemented'          => [],
                'missing'              => [],
                'quality_notes'        => [],
                'missing_requirements' => [],
            ];
        }
    }
}

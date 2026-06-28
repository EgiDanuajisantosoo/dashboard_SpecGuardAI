<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->apiKey  = config('openai.api_key', env('OPENAI_API_KEY', ''));
        $this->model   = env('OPENAI_MODEL', 'gemini-1.5-flash');
        $this->baseUrl = rtrim(env('OPENAI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai'), '/');
        $this->timeout = (int) env('OPENAI_REQUEST_TIMEOUT', 120);
    }

    // -------------------------------------------------------------------------
    // 1. Generate Mermaid Diagram from PRD
    // -------------------------------------------------------------------------

    /**
     * Generate a Mermaid flowchart from PRD text + requirements.
     */
    public function generateMermaid(string $prdContent, array $requirements = []): string
    {
        $reqString = $this->formatRequirements($requirements);

        $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
            'model'    => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $this->getMermaidSystemPrompt()],
                [
                    'role' => 'user',
                    'content' => "PRD CONTENT:\n" . $prdContent
                        . "\n\nCORE REQUIREMENTS (MUST SYNC):\n" . $reqString,
                ],
            ],
            'max_tokens' => 2500,
        ]);

        $raw = $response->choices[0]->message->content ?? '';
        return $this->sanitizeMermaid($raw);
    }

    /**
     * Regenerate Mermaid for an existing project (uses HTTP client for temperature control).
     */
    public function regenerateMermaid(string $prdContent, array $requirements = []): string
    {
        $reqString = $this->formatRequirements($requirements);

        $response = Http::timeout($this->timeout)
            ->withToken($this->apiKey)
            ->post("{$this->baseUrl}/chat/completions", [
                'model'       => $this->model,
                'messages'    => [
                    ['role' => 'system', 'content' => $this->getMermaidSystemPrompt()],
                    [
                        'role' => 'user',
                        'content' => "PRD CONTENT:\n" . substr($prdContent, 0, 3000)
                            . "\n\nCORE REQUIREMENTS (MUST SYNC):\n" . $reqString,
                    ],
                ],
                'max_tokens'  => 2000,
                'temperature' => 0.3,
            ]);

        if (!$response->successful()) {
            throw new \Exception("API error {$response->status()}: " . $response->body());
        }

        $raw = $response->json('choices.0.message.content', '');
        if (empty(trim($raw))) {
            throw new \Exception('AI returned empty response.');
        }

        return $this->sanitizeMermaid($raw);
    }

    // -------------------------------------------------------------------------
    // 2. Audit Compliance from Git Diff / Codebase
    // -------------------------------------------------------------------------

    /**
     * Run a compliance audit comparing codebase against PRD + spec.
     * Returns structured audit result array.
     */
    public function auditCompliance(string $prdContent, string $specContent, string $codebaseContent, string $commitHash): array
    {
        $prdContent  = substr($prdContent, 0, 1200);
        $specSummary = $this->extractMermaidFeatures($specContent);

        $systemPrompt = $this->getAuditSystemPrompt();

        $userMessage = <<<MSG
PRD:
{$prdContent}

MERMAID SPEC:
{$specSummary}

CODEBASE AT COMMIT {$commitHash}:
{$codebaseContent}

Audit the codebase against the PRD. Return ONLY the complete JSON with detailed per-feature reviews.
MSG;

        $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
            'model'      => $this->model,
            'messages'   => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $userMessage],
            ],
            'max_tokens' => 2500,
        ]);

        $content = $response->choices[0]->message->content ?? '';
        return $this->parseAuditResponse($content, $specContent);
    }

    // -------------------------------------------------------------------------
    // 3. Extract Requirements from PRD
    // -------------------------------------------------------------------------

    /**
     * Extract key requirements / acceptance criteria from PRD text.
     * Returns array of [{title, description, type}].
     */
    public function extractRequirements(string $prdContent): array
    {
        try {
            $response = Http::timeout(30)
                ->withToken($this->apiKey)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model'       => $this->model,
                    'temperature' => 0.2,
                    'max_tokens'  => 800,
                    'messages'    => [
                        [
                            'role'    => 'system',
                            'content' => 'You are a business analyst. Extract 4-8 key requirements or acceptance criteria from the PRD. Output ONLY a valid JSON array. No markdown, no explanation.

FORMAT: [{"title":"Short title","description":"One sentence description","type":"functional|non-functional|security|ui"}]

EXAMPLE: [{"title":"User Registration","description":"Users can register with email and password.","type":"functional"}]',
                        ],
                        [
                            'role'    => 'user',
                            'content' => substr($prdContent, 0, 2000),
                        ],
                    ],
                ]);

            if (!$response->successful()) return [];

            $content = trim($response->json('choices.0.message.content', ''));
            $content = preg_replace('/^```(?:json)?\n?/m', '', $content);
            $content = preg_replace('/```$/m', '', $content);
            $content = trim($content);

            $parsed = json_decode($content, true);
            return is_array($parsed) ? array_slice($parsed, 0, 8) : [];
        } catch (\Exception) {
            return [];
        }
    }

    // -------------------------------------------------------------------------
    // 4. Generate Recommended Implementation Prompts
    // -------------------------------------------------------------------------

    /**
     * Generate AI-powered implementation prompts/recommendations for missing features.
     */
    public function generateRecommendations(string $prdContent, array $missingFeatures, array $partialFeatures = []): array
    {
        if (empty($missingFeatures) && empty($partialFeatures)) {
            return [];
        }

        $featureList = '';
        foreach ($missingFeatures as $f) {
            $featureList .= "- [MISSING] " . ucfirst(str_replace('_', ' ', $f)) . "\n";
        }
        foreach ($partialFeatures as $f) {
            $featureList .= "- [PARTIAL] " . ucfirst(str_replace('_', ' ', $f)) . "\n";
        }

        try {
            $response = Http::timeout(30)
                ->withToken($this->apiKey)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model'       => $this->model,
                    'temperature' => 0.3,
                    'max_tokens'  => 1200,
                    'messages'    => [
                        [
                            'role'    => 'system',
                            'content' => 'You are a Laravel implementation expert. Given a PRD and a list of missing/partial features, generate concise implementation prompts that a developer can use to build each feature. Output ONLY a JSON array. No markdown.

FORMAT: [{"feature":"feature_name","prompt":"Step-by-step implementation instruction","priority":"high|medium|low","files":["app/Http/Controllers/ExampleController.php"]}]',
                        ],
                        [
                            'role'    => 'user',
                            'content' => "PRD:\n" . substr($prdContent, 0, 1500) . "\n\nFEATURES TO IMPLEMENT:\n" . $featureList,
                        ],
                    ],
                ]);

            if (!$response->successful()) return [];

            $content = trim($response->json('choices.0.message.content', ''));
            $content = preg_replace('/^```(?:json)?\n?/m', '', $content);
            $content = preg_replace('/```$/m', '', $content);

            $parsed = json_decode(trim($content), true);
            return is_array($parsed) ? $parsed : [];
        } catch (\Exception) {
            return [];
        }
    }

    // =========================================================================
    // Private helpers
    // =========================================================================

    private function formatRequirements(array $requirements): string
    {
        $str = '';
        foreach ($requirements as $i => $req) {
            $str .= ($i + 1) . '. ' . ($req['title'] ?? '') . ': ' . ($req['description'] ?? '') . "\n";
        }
        return $str;
    }

    /**
     * Extract node labels from Mermaid spec to create a compact summary.
     */
    public function extractMermaidFeatures(string $specContent): string
    {
        if (empty(trim($specContent))) {
            return 'No spec provided.';
        }

        preg_match_all('/\w+[\[\(\{]([^\]\)\}]{3,50})[\]\)\}]/', $specContent, $matches);
        $labels = array_unique($matches[1] ?? []);
        $labels = array_filter($labels, fn($l) =>
            !preg_match('/^(true|false|yes|no|null|\d+)$/i', $l) && strlen(trim($l)) > 2
        );

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
     * Parse raw AI audit response into structured array.
     */
    private function parseAuditResponse(string $content, string $specContent): array
    {
        $content = preg_replace('/^```(?:json)?\n?/m', '', $content);
        $content = preg_replace('/```$/m', '', $content);
        $content = trim($content);

        $result = json_decode($content, true);

        if (!is_array($result)) {
            // Regex fallback
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
                    'status' => 'failed', 'score' => 0,
                    'error' => 'AI returned invalid JSON: ' . substr($content, 0, 300),
                    'node_status' => [], 'implemented' => [], 'missing' => [],
                    'quality_notes' => [], 'missing_requirements' => [],
                ];
            }
        }

        // Build node_status by matching features to Mermaid tokens
        $implemented = $result['implemented'] ?? [];
        $partial     = $result['partial']     ?? [];
        $missing     = $result['missing']     ?? [];
        $allFeatures = array_unique(array_merge($implemented, $partial, $missing));
        $nodeStatus  = [];

        preg_match_all('/\b([A-Za-z][A-Za-z0-9_]{2,})\b/', $specContent, $nodeMatches);
        $specTokens = array_unique($nodeMatches[1] ?? []);

        foreach ($allFeatures as $feature) {
            $featureWords = array_filter(explode('_', strtolower($feature)), fn($w) => strlen($w) >= 4);
            $isImpl  = in_array($feature, $implemented);
            $isPart  = in_array($feature, $partial);
            $status  = $isImpl ? true : ($isPart ? 'partial' : false);

            foreach ($specTokens as $token) {
                $tokenLower = strtolower($token);
                foreach ($featureWords as $word) {
                    if (str_contains($tokenLower, $word)) {
                        $nodeStatus[$token] = $status;
                        break;
                    }
                }
            }
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
    }

    /**
     * Sanitize raw Mermaid output from AI.
     */
    public function sanitizeMermaid(string $raw): string
    {
        $code = preg_replace('/^```(?:mermaid)?\s*/im', '', $raw);
        $code = preg_replace('/^```\s*$/m', '', $code);
        $code = trim($code);

        // Fix invalid pipe-arrow
        $code = preg_replace('/\|([^|\n]{0,80})\|>/', '|$1|', $code);

        // Replace & with "and" inside labels
        $code = preg_replace_callback(
            '/([\[\{\(][^\/\]\}\)]*)[&]([^\]\}\)]*[\]\}\)])/u',
            fn($m) => str_replace('&', ' and ', $m[0]),
            $code
        );

        // Remove orphan `end` lines
        $lines      = explode("\n", $code);
        $inSubgraph = 0;
        $cleaned    = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (preg_match('/^subgraph\b/i', $trimmed)) {
                $inSubgraph++;
                $cleaned[] = $line;
            } elseif ($trimmed === 'end') {
                if ($inSubgraph > 0) {
                    $inSubgraph--;
                    $cleaned[] = $line;
                }
            } else {
                $cleaned[] = $line;
            }
        }
        $code = implode("\n", $cleaned);

        // Deduplicate arrow lines
        $arrowLines = [];
        $deduped    = [];
        foreach (explode("\n", $code) as $line) {
            $t = trim($line);
            if (preg_match('/-->|---/', $t)) {
                $key = preg_replace('/\s+/', ' ', $t);
                if (isset($arrowLines[$key])) continue;
                $arrowLines[$key] = true;
            }
            $deduped[] = $line;
        }
        $code = implode("\n", $deduped);

        // Fix style lines
        $code = preg_replace('/style (\w+) fill=/', 'style $1 fill:', $code);

        return trim($code);
    }

    /**
     * Mermaid system prompt for flowchart generation.
     */
    private function getMermaidSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert system architect and software engineer. Your task is to convert a PRD (Product Requirements Document) into a valid, professional Mermaid.js flowchart that adheres to universal software engineering standards.

=== SYNC REQUIREMENT (CRITICAL) ===
You will be provided with a list of "Core Requirements" (Acceptance Criteria). 
Every single requirement listed MUST be represented as at least one node or a clear path in the flowchart. 
Ensure the flowchart is 100% synchronized with these requirements.

=== OUTPUT RULES (MANDATORY) ===
1. Output ONLY raw Mermaid code. NO markdown fences (```), NO explanation, NO comments.
2. First line MUST be: graph TD  OR  graph LR  (choose based on complexity; LR for simple linear flows, TD for branching).
3. Arrow syntax: A -->|label| B   NEVER use A -->|label|> B
4. Max 20 nodes. modularize but ensure completeness.
5. All subgraphs must be closed with "end".

=== UNIVERSAL FLOWCHART STANDARDS ===

STRUCTURE:
Every flowchart MUST follow: Start → Input → Validation → Process → Decision → Output → End

NODE RULES:
- [Rectangle] = Process / Action ("Save User", "Generate Token", "Send Email")
- {Diamond}   = Decision — MUST be a YES/NO question ("Email Valid?", "Token Exists?", "Payment Success?")
- ([Stadium])  = Start / End state
- Every Decision MUST have at least 2 branches (Yes/No or True/False)
- Node names: 2–5 words MAX. No full sentences in nodes.
- 1 node = 1 responsibility. Never combine multiple actions in one node.

FLOW RULES:
- Consistent direction (do not mix TD and LR in same flow)
- No crossing arrows
- Error flows are MANDATORY and must be recoverable (not dead-ends)
- Validation MUST occur before: database save, payment, auth, API call, transaction
- State transitions must be explicit and logical

NAMING CONVENTIONS:
- Good: "Verify Password", "Create Session", "Check Permission", "Save User"
- Bad: "Database", "Email", "Process", "Handle"

=== CHECKLIST BEFORE OUTPUT ===
✓ ALL provided "Core Requirements" are represented in the nodes
✓ Has Start and End nodes
✓ All decisions are YES/NO questions
✓ All processes are action verbs
✓ Error flows loop back (not dead-end)
✓ Validation before every major operation
✓ No node has more than 5 words
✓ No crossing arrows
SYSTEM;
    }

    /**
     * Audit system prompt for compliance checking.
     */
    private function getAuditSystemPrompt(): string
    {
        return <<<PROMPT
You are SpecGuard AI — a senior compliance auditor. Your job is to compare a CODEBASE against the PRD and produce a detailed audit report.

STEP 1 — Read the PRD carefully. Identify ALL features/requirements mentioned.
STEP 2 — Read the CODEBASE. For each feature, determine: implemented, partial, or missing.
STEP 3 — Output ONLY a valid JSON object. No markdown. No backticks. No explanation.

FEATURE CLASSIFICATION:
- "implemented": Feature fully exists in code AND matches PRD specification.
- "partial": Feature code exists BUT has gaps, missing validation, incomplete logic, or deviates from PRD.
- "missing": No relevant file, route, controller, or model found at all.

SCORING: Start 0. +15 per implemented. +8 per partial. +5 bonus for auth middleware. -10 per PRD deviation. Max 100.
status = "complete" if score>=80, "partial" if 40-79, "failed" if <40.

FEATURE KEY RULES:
- Keys must be short snake_case strings derived from the PRD (e.g. "user_registration", "payment_gateway").
- Do NOT use hardcoded/generic names. Derive entirely from PRD content.

REVIEW DETAIL RULES (CRITICAL):
For each feature in "partial" → write a "reviews" entry explaining what exists and what is missing.
For each feature in "missing" → write a "reviews" entry explaining what the PRD requires and what should be created.

OUTPUT FORMAT:
{
  "implemented": ["feature_a", "feature_b"],
  "partial": ["feature_c"],
  "missing": ["feature_d"],
  "score": 65,
  "status": "partial",
  "summary": "One sentence max 120 chars",
  "quality_notes": ["max 3 overall notes about code quality"],
  "reviews": {
    "feature_c": {
      "status": "partial",
      "found": "What exists in code",
      "issue": "What is missing compared to PRD",
      "recommendation": "Specific fix recommendation",
      "priority": "high"
    }
  }
}

Output ONLY the JSON. Nothing else.
PROMPT;
    }
}

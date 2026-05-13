<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Audit;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('audits')->get();

        return view('dashboard', ['projects' => $projects]);
    }

    public function show(Project $project): View
    {
        $project->load(['audits' => function ($query) {
            $query->latest('created_at')->limit(20);
        }]);

        $latestAudit = $project->audits()->latest('created_at')->first();

        return view('compliance', [
            'project' => $project,
            'latestAudit' => $latestAudit,
        ]);
    }

    public function audits(Project $project)
    {
        $audits = Audit::where('project_id', $project->id)
            ->latest('created_at')
            ->paginate(10);

        return response()->json($audits);
    }

    public function openspec(\Illuminate\Http\Request $request)
    {
        $projectId = $request->query('project');
        $project = null;

        if ($projectId) {
            $project = Project::find($projectId);
        } else {
            // Default to the most recently created project
            $project = Project::latest()->first();
        }

        return view('mermaid-previewer', ['project' => $project]);
    }

    public function compliance(\Illuminate\Http\Request $request)
    {
        $projectId = $request->query('project');

        if ($projectId) {
            $project = Project::with('audits')->find($projectId);
        } else {
            // Default to the most recently updated project
            $project = Project::with('audits')->latest()->first();
        }

        if (!$project) {
            return redirect()->route('dashboard')->with('error', 'No project found. Please generate a spec first.');
        }

        $latestAudit = $project->audits()->latest('created_at')->first();

        return view('compliance', [
            'project' => $project,
            'latestAudit' => $latestAudit,
        ]);
    }

    public function generate(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'project_area' => 'required|string',
            'target_repo' => 'required|string',
            'raw_text' => 'required|string',
        ]);

        try {
            // 1. Extract requirements first to sync with flowchart
            $requirements = $this->extractRequirements($request->raw_text);
            $reqString = "";
            foreach ($requirements as $index => $req) {
                $reqString .= ($index + 1) . ". " . $req['title'] . ": " . $req['description'] . "\n";
            }

            // 2. Generate Mermaid using PRD + extracted Requirements
            $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
                'model'    => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => $this->getMermaidSystemPrompt()],
                    [
                        'role' => 'user',
                        'content' => "PRD CONTENT:\n" . $request->raw_text . "\n\nCORE REQUIREMENTS (MUST SYNC):\n" . $reqString
                    ],
                ],
                'max_tokens' => 2500,
            ]);

            $mermaidCode = $response->choices[0]->message->content;

            // Sanitize the Mermaid code before saving
            $mermaidCode = $this->sanitizeMermaid($mermaidCode);

            $project = Project::create([
                'name'             => $request->project_area,
                'repo_url'         => $request->target_repo,
                'prd_content'      => $request->raw_text,
                'prd_requirements' => $requirements,
                'spec_content'     => $mermaidCode,
            ]);

            return redirect()->route('openspec', ['project' => $project->id]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate OpenSpec: ' . $e->getMessage());
        }
    }

    /**
     * Sanitize raw Mermaid output from AI to fix common syntax errors.
     * Handles: orphan `end`, & chars, duplicate arrows, invalid arrow syntax, style errors.
     */
    private function sanitizeMermaid(string $raw): string
    {
        // 1. Strip markdown fences
        $code = preg_replace('/^```(?:mermaid)?\s*/im', '', $raw);
        $code = preg_replace('/^```\s*$/m', '', $code);
        $code = trim($code);

        // 2. Fix invalid pipe-arrow: -->|text|> → -->|text|
        $code = preg_replace('/\|([^|\n]{0,80})\|>/', '|$1|', $code);

        // 3. Replace & with "and" inside node labels (causes Mermaid parse error)
        //    Only inside brackets/labels, not in arrows
        $code = preg_replace_callback(
            '/([\[\{\(][^\/\]\}\)]*)[&]([^\]\}\)]*[\]\}\)])/u',
            fn($m) => str_replace('&', ' and ', $m[0]),
            $code
        );

        // 4. Remove orphan `end` lines (not preceded by subgraph definition)
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
                // else: skip orphan `end`
            } else {
                $cleaned[] = $line;
            }
        }
        $code = implode("\n", $cleaned);

        // 5. Remove duplicate arrow lines (keep first occurrence)
        $arrowLines = [];
        $deduped    = [];
        foreach (explode("\n", $code) as $line) {
            $t = trim($line);
            // A line is an arrow if it contains --> or ---
            if (preg_match('/-->|---/', $t)) {
                $key = preg_replace('/\s+/', ' ', $t); // normalize whitespace
                if (isset($arrowLines[$key])) {
                    continue; // skip duplicate
                }
                $arrowLines[$key] = true;
            }
            $deduped[] = $line;
        }
        $code = implode("\n", $deduped);

        // 6. Fix style lines: fill= → fill:
        $code = preg_replace('/style (\w+) fill=/', 'style $1 fill:', $code);

        return trim($code);
    }

    /**
     * Universal Mermaid flowchart system prompt based on software engineering standards.
     * Used by both generate() and regenerate() for consistent output.
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
- Error flows are MANDATORY and must be recoverable (not dead-ends), e.g., loop back to form or show retry
- Validation MUST occur before: database save, payment, auth, API call, transaction
- State transitions must be explicit and logical

SECURITY RULES (if system has auth/payment/API):
- Always show: Validation → Authorization → Verification → Error Handling
- Sessions / tokens must have expiry / invalid state branches

HIDDEN ASSUMPTIONS TO ALWAYS INCLUDE:
- Systems have states (Guest, Authenticated, Pending, Paid, Cancelled)
- All decisions have consequences that change the next state
- Error handling is part of the system, not optional
- Validation happens before every important operation

NAMING CONVENTIONS:
- Good: "Verify Password", "Create Session", "Check Permission", "Save User"
- Bad: "Database", "Email", "Process", "Handle"

VALID EXAMPLE:
graph LR
    A([Start]) --> B[/Input Email & Password/]
    B --> C{Email Registered?}
    C -->|No| D[Show Error] --> B
    C -->|Yes| E{Password Match?}
    E -->|No| F[Show Error] --> B
    E -->|Yes| G[Create Session]
    G --> H[/Redirect to Dashboard/]
    H --> I([End])

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
     * Extract key requirements/AC from PRD text via AI.
     * Returns array of [{title, description, type}] or empty array on failure.
     */
    private function extractRequirements(string $prdContent): array
    {
        try {
            $apiKey  = env('OPENAI_API_KEY');
            $model   = env('OPENAI_MODEL', 'llama-3.3-70b-versatile');
            $baseUrl = rtrim(env('OPENAI_BASE_URL', 'https://api.groq.com/openai/v1'), '/');

            $response = \Illuminate\Support\Facades\Http::timeout(30)
                ->withToken($apiKey)
                ->post("{$baseUrl}/chat/completions", [
                    'model'       => $model,
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

    /**
     * Re-generate spec_content from existing prd_content for a project.
     */
    public function regenerate(\Illuminate\Http\Request $request, Project $project)
    {
        if (empty($project->prd_content)) {
            return redirect()
                ->route('openspec', ['project' => $project->id])
                ->with('error', 'No PRD content found. Please create a new project with a PRD first.');
        }

        try {
            // 1. Ensure requirements are extracted
            $requirements = $project->prd_requirements;
            if (empty($requirements)) {
                $requirements = $this->extractRequirements($project->prd_content);
            }

            $reqString = "";
            foreach ($requirements as $index => $req) {
                $reqString .= ($index + 1) . ". " . $req['title'] . ": " . $req['description'] . "\n";
            }

            // 2. Generate Mermaid using PRD + extracted Requirements
            $apiKey   = env('OPENAI_API_KEY');
            $model    = env('OPENAI_MODEL', 'llama-3.3-70b-versatile');
            $baseUrl  = rtrim(env('OPENAI_BASE_URL', 'https://api.groq.com/openai/v1'), '/');

            $response = \Illuminate\Support\Facades\Http::timeout(45)
                ->withToken($apiKey)
                ->post("{$baseUrl}/chat/completions", [
                    'model'       => $model,
                    'messages'    => [
                        ['role' => 'system', 'content' => $this->getMermaidSystemPrompt()],
                        [
                            'role' => 'user',
                            'content' => "PRD CONTENT:\n" . substr($project->prd_content, 0, 3000) . "\n\nCORE REQUIREMENTS (MUST SYNC):\n" . $reqString
                        ],
                    ],
                    'max_tokens'  => 2000,
                    'temperature' => 0.3,
                ]);

            if (!$response->successful()) {
                throw new \Exception("API error {$response->status()}: " . $response->body());
            }

            $mermaidCode = $response->json('choices.0.message.content', '');

            if (empty(trim($mermaidCode))) {
                throw new \Exception('AI returned empty response.');
            }

            // Sanitize: strip fences, fix & chars, remove orphan end, dedupe arrows
            $mermaidCode = $this->sanitizeMermaid($mermaidCode);

            $project->update([
                'spec_content'     => $mermaidCode,
                'prd_requirements' => $requirements, // update in case it was empty
            ]);

            return redirect()
                ->route('openspec', ['project' => $project->id])
                ->with('success', 'Spec & Requirements berhasil di-regenerate dari PRD.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Regenerate failed', [
                'project_id' => $project->id,
                'error'      => $e->getMessage(),
            ]);
            return redirect()
                ->route('openspec', ['project' => $project->id])
                ->with('error', 'Gagal regenerate: ' . $e->getMessage());
        }
    }
}

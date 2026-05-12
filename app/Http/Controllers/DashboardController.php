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

        return view('openspec', ['project' => $project]);
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
            $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert system architect. Convert PRD text into valid Mermaid.js flowchart code.

                        STRICT RULES:
                        1. Output ONLY raw Mermaid code. No markdown blocks (```).
                        2. Use ONLY valid arrow syntax: A -->|label| B  (NOT A -->|label|> B)
                        3. Arrow labels use single pipe on each side: -->|text| NOT -->|text|>
                        4. Use graph TD or graph LR as the first line.
                        5. Keep it simple — max 15 nodes. Simplify complex PRDs.
                        6. Ensure all subgraphs are properly closed with "end".

                        VALID EXAMPLE:
                        graph LR
                            A[Start] -->|click| B{Check}
                            B -->|yes| C[Success]
                            B -->|no| A',
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->raw_text,
                    ],
                ],
                'max_tokens' => 2500,
            ]);

            $mermaidCode = $response->choices[0]->message->content;

            // Clean up markdown blocks
            $mermaidCode = preg_replace('/^```(?:mermaid)?\n?/m', '', $mermaidCode);
            $mermaidCode = preg_replace('/```$/m', '', $mermaidCode);
            $mermaidCode = trim($mermaidCode);

            // Auto-fix common AI mistakes in Mermaid syntax
            // Fix invalid arrow: -->|text|> → -->|text|
            $mermaidCode = preg_replace('/\|([^|\n]{0,80})\|>/', '|$1|', $mermaidCode);
            // Fix style lines that use = instead of : (common mistake)
            $mermaidCode = preg_replace('/style (\w+) fill=/', 'style $1 fill:', $mermaidCode);

            // Extract key requirements from PRD in background (non-blocking)
            $requirements = $this->extractRequirements($request->raw_text);

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
            $apiKey   = env('OPENAI_API_KEY');
            $model    = env('OPENAI_MODEL', 'llama-3.3-70b-versatile');
            $baseUrl  = rtrim(env('OPENAI_BASE_URL', 'https://api.groq.com/openai/v1'), '/');

            $systemPrompt = 'You are an expert system architect. Convert PRD text into valid Mermaid.js flowchart code.

STRICT RULES:
1. Output ONLY raw Mermaid code. No markdown blocks (```).
2. Use ONLY valid arrow syntax: A -->|label| B
3. Use graph TD or graph LR as the first line.
4. Keep it simple — max 12 nodes. Simplify complex PRDs.
5. All subgraphs must be closed with "end".

EXAMPLE:
graph LR
    A[Start] -->|click| B{Check}
    B -->|yes| C[Success]
    B -->|no| A';

            $response = \Illuminate\Support\Facades\Http::timeout(45)
                ->withToken($apiKey)
                ->post("{$baseUrl}/chat/completions", [
                    'model'      => $model,
                    'messages'   => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => substr($project->prd_content, 0, 3000)],
                    ],
                    'max_tokens' => 1000,
                    'temperature' => 0.3,
                ]);

            if (!$response->successful()) {
                throw new \Exception("API error {$response->status()}: " . $response->body());
            }

            $mermaidCode = $response->json('choices.0.message.content', '');

            if (empty(trim($mermaidCode))) {
                throw new \Exception('AI returned empty response.');
            }

            // Clean up markdown blocks
            $mermaidCode = preg_replace('/^```(?:mermaid)?\n?/m', '', $mermaidCode);
            $mermaidCode = preg_replace('/```$/m', '', $mermaidCode);
            $mermaidCode = trim($mermaidCode);

            // Auto-fix common AI Mermaid syntax mistakes
            $mermaidCode = preg_replace('/\|([^|\n]{0,80})\|>/', '|$1|', $mermaidCode);
            $mermaidCode = preg_replace('/style (\w+) fill=/', 'style $1 fill:', $mermaidCode);

            // Also re-extract requirements
            $requirements = $this->extractRequirements($project->prd_content);

            $project->update([
                'spec_content'     => $mermaidCode,
                'prd_requirements' => empty($requirements) ? $project->prd_requirements : $requirements,
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

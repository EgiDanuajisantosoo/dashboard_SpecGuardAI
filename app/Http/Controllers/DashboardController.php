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

            $project = Project::create([
                'name' => $request->project_area,
                'repo_url' => $request->target_repo,
                'prd_content' => $request->raw_text,
                'spec_content' => $mermaidCode,
            ]);

            return redirect()->route('openspec', ['project' => $project->id]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate OpenSpec: ' . $e->getMessage());
        }
    }
}

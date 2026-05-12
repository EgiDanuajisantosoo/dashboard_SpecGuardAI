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
        }

        return view('openspec', ['project' => $project]);
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
                        'content' => 'You are an expert system architect and auditor. Your task is to convert raw Product Requirements Documents (PRD) into a Mermaid.js flowchart code representing the system architecture or flow. Output ONLY the raw Mermaid code, without any markdown formatting blocks like ```mermaid or ```.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->raw_text,
                    ],
                ],
                'max_tokens' => 1500,
            ]);

            $mermaidCode = $response->choices[0]->message->content;
            
            // Cleanup any markdown blocks just in case
            $mermaidCode = preg_replace('/```(mermaid)?/', '', $mermaidCode);
            $mermaidCode = trim($mermaidCode);

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

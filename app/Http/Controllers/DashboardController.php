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
}

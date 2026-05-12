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
            'project_id' => $this->projectId,
            'commit_hash' => $this->commitHash,
            'status' => 'pending',
        ]);

        try {
            $result = $this->callAIEngine($project, $this->diff);

            $audit->update([
                'score' => $result['score'] ?? 0,
                'status' => $result['status'] ?? 'failed',
                'result_json' => $result,
            ]);
        } catch (\Exception $e) {
            $audit->update([
                'status' => 'failed',
                'result_json' => ['error' => $e->getMessage()],
            ]);
        }
    }

    private function callAIEngine(Project $project, string $diff): array
    {
        $aiServiceUrl = env('AI_SERVICE_URL', 'http://localhost:8000');

        $response = Http::post("{$aiServiceUrl}/api/audit", [
            'spec' => $project->spec_content,
            'diff' => $diff,
        ]);

        return $response->json();
    }
}

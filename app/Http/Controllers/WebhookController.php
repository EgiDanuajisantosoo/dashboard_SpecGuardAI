<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAuditJob;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WebhookController extends Controller
{
    public function github(Request $request): JsonResponse
    {
        $signature = $request->header('X-Hub-Signature-256');
        $payload = $request->getContent();

        if (!$this->verifyGitHubSignature($payload, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = $request->json()->all();

        if ($data['action'] !== 'opened' && $data['action'] !== 'synchronize') {
            return response()->json(['message' => 'Ignored event'], 200);
        }

        $project = Project::where('repo_url', $data['repository']['clone_url'])->first();

        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $commitHash = $data['pull_request']['head']['sha'];
        $diff = $this->getGitHubDiff($data);

        ProcessAuditJob::dispatch($project->id, $commitHash, $diff);

        return response()->json(['message' => 'Audit queued'], 202);
    }

    private function verifyGitHubSignature($payload, $signature): bool
    {
        if (!$signature) {
            return false;
        }

        $secret = env('GITHUB_WEBHOOK_SECRET', '');
        $hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        return hash_equals($hash, $signature);
    }

    private function getGitHubDiff(array $data): string
    {
        return $data['pull_request']['diff_url'] ?? '';
    }
}

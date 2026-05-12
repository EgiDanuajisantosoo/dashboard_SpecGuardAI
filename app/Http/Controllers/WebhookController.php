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

        // Handle Ping event from GitHub
        $eventName = $request->header('X-GitHub-Event');

        if ($eventName === 'ping') {
            return response()->json(['message' => 'pong'], 200);
        }

        if ($eventName !== 'push' && $eventName !== 'pull_request') {
            return response()->json(['message' => 'Ignored event: ' . $eventName], 200);
        }

        $projectUrl = $data['repository']['clone_url'] ?? '';
        $htmlUrl = $data['repository']['html_url'] ?? '';
        $project = Project::where('repo_url', $projectUrl)->orWhere('repo_url', $htmlUrl)->first();

        if (!$project) {
            return response()->json(['error' => 'Project not found: ' . $projectUrl], 404);
        }

        $commitHash = '';
        $diffUrl = '';

        if ($eventName === 'push') {
            $commitHash = $data['head_commit']['id'] ?? '';
            // For push events, append .diff to the commit URL to get the raw diff
            $commitUrl = $data['head_commit']['url'] ?? '';
            $diffUrl = $commitUrl ? $commitUrl . '.diff' : '';
        } elseif ($eventName === 'pull_request') {
            $commitHash = $data['pull_request']['head']['sha'] ?? '';
            $diffUrl = $data['pull_request']['diff_url'] ?? '';
        }

        if (empty($commitHash)) {
            return response()->json(['error' => 'No commit hash found'], 400);
        }

        ProcessAuditJob::dispatch($project->id, $commitHash, $diffUrl);

        return response()->json(['message' => 'Audit queued for commit ' . $commitHash], 202);
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
}

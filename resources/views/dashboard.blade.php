<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpecGuard AI - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
    <script>
        mermaid.initialize({ startOnLoad: true, theme: 'default' });
    </script>
    <style>
        .mermaid svg { max-height: 400px; }
        .node-complete { fill: #10b981 !important; }
        .node-partial { fill: #f59e0b !important; }
        .node-missing { fill: #ef4444 !important; }
        .node-empty { fill: #d1d5db !important; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <h1 class="text-3xl font-bold text-gray-900">SpecGuard AI</h1>
                <p class="text-gray-600 mt-1">AI-Powered Specification Compliance Auditor</p>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-12">
            @if($projects->isEmpty())
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                    <p class="text-blue-800">No projects found. Create a new project to get started.</p>
                </div>
            @else
                <div class="grid gap-6">
                    @foreach($projects as $project)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h2>
                                        <p class="text-sm text-gray-600 mt-1">{{ $project->repo_url }}</p>
                                    </div>
                                    <a href="{{ route('project.show', $project) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            <div class="px-6 py-4">
                                @if($project->audits->isNotEmpty())
                                    @php
                                        $latestAudit = $project->audits->first();
                                    @endphp
                                    <div class="mb-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-medium text-gray-700">Compliance Score</span>
                                            <span class="text-2xl font-bold text-gray-900">{{ $latestAudit->score }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-{{ $latestAudit->score >= 80 ? 'green-500' : ($latestAudit->score >= 50 ? 'yellow-500' : 'red-500') }} h-2 rounded-full"
                                                 style="width: {{ $latestAudit->score }}%"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Status:</span>
                                            <span class="ml-2 px-2 py-1 rounded text-white text-xs font-semibold
                                                {{ $latestAudit->status === 'complete' ? 'bg-green-500' :
                                                   ($latestAudit->status === 'partial' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                                {{ ucfirst($latestAudit->status) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Commit:</span>
                                            <span class="ml-2 font-mono text-xs">{{ substr($latestAudit->commit_hash, 0, 8) }}</span>
                                        </div>
                                    </div>

                                    <!-- Mermaid Visualization -->
                                    <div class="mt-4 p-4 bg-gray-50 rounded overflow-x-auto">
                                        <div class="mermaid" id="mermaid-{{ $project->id }}">
                                            graph TD
                                            A[Authentication]
                                            B[Validation]
                                            C[Logging]

                                            A --> B
                                            B --> C
                                        </div>
                                    </div>

                                    @if($latestAudit->result_json)
                                        <div class="mt-4 text-sm">
                                            <h4 class="font-semibold text-gray-900 mb-2">Details:</h4>
                                            @if(isset($latestAudit->result_json['missing_requirements']))
                                                <div class="bg-red-50 p-3 rounded">
                                                    <p class="text-red-800 font-medium text-xs">Missing Requirements:</p>
                                                    <ul class="text-red-700 text-xs list-disc list-inside mt-1">
                                                        @foreach($latestAudit->result_json['missing_requirements'] as $req)
                                                            <li>{{ $req }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    <p class="text-gray-600 text-center py-8">No audits yet. Push to GitHub to trigger an audit.</p>
                                @endif
                            </div>

                            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-xs text-gray-600">
                                Last updated: {{ $project->audits->first()?->created_at?->diffForHumans() ?? 'Never' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>

    <script>
        function updateMermaidColors(projectId, nodeStatus) {
            const element = document.getElementById('mermaid-' + projectId);
            if (!element) return;

            setTimeout(() => {
                const svg = element.querySelector('svg');
                if (!svg) return;

                for (const [node, status] of Object.entries(nodeStatus)) {
                    const nodeElements = svg.querySelectorAll(`[class*="${node}"]`);
                    nodeElements.forEach(el => {
                        if (status) {
                            el.classList.add('node-complete');
                        } else {
                            el.classList.add('node-missing');
                        }
                    });
                }
            }, 100);
        }
    </script>
</body>
</html>

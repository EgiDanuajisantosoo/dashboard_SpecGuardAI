<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} - SpecGuard AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
    <script>
        mermaid.initialize({ startOnLoad: true, theme: 'default' });
    </script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="flex items-center mb-4">
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 mr-2">← Back</a>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                </div>
                <p class="text-gray-600">{{ $project->repo_url }}</p>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-12">
            <!-- Current Status -->
            @if($latestAudit)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Latest Audit</h2>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Compliance Score</p>
                            <p class="text-4xl font-bold text-gray-900 mt-2">{{ $latestAudit->score }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="mt-2 px-3 py-1 rounded text-white text-sm font-semibold inline-block
                                {{ $latestAudit->status === 'complete' ? 'bg-green-500' :
                                   ($latestAudit->status === 'partial' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                {{ ucfirst($latestAudit->status) }}
                            </p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Progress</span>
                            <span class="text-sm text-gray-600">{{ $latestAudit->score }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-{{ $latestAudit->score >= 80 ? 'green-500' : ($latestAudit->score >= 50 ? 'yellow-500' : 'red-500') }} h-3 rounded-full transition-all"
                                 style="width: {{ $latestAudit->score }}%"></div>
                        </div>
                    </div>

                    <!-- Mermaid Diagram -->
                    <div class="p-4 bg-gray-50 rounded overflow-x-auto mb-6">
                        <div class="mermaid">
                            graph TD
                            A[Authentication]
                            B[Validation]
                            C[Logging]

                            A --> B
                            B --> C
                        </div>
                    </div>

                    <!-- Missing Requirements -->
                    @if($latestAudit->result_json && isset($latestAudit->result_json['missing_requirements']))
                        <div class="bg-red-50 border border-red-200 rounded p-4">
                            <h3 class="font-semibold text-red-900 mb-2">Missing Requirements</h3>
                            <ul class="text-red-800 text-sm space-y-1 list-disc list-inside">
                                @foreach($latestAudit->result_json['missing_requirements'] as $req)
                                    <li>{{ $req }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-4 text-xs text-gray-600">
                        <p>Commit: <span class="font-mono">{{ $latestAudit->commit_hash }}</span></p>
                        <p>Audited: {{ $latestAudit->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded p-6 text-center">
                    <p class="text-blue-800">No audits yet. Push to GitHub to trigger an audit.</p>
                </div>
            @endif

            <!-- Audit History -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Audit History</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Commit</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Score</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($project->audits as $audit)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ substr($audit->commit_hash, 0, 8) }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $audit->score }}%</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 rounded text-white text-xs font-semibold
                                            {{ $audit->status === 'complete' ? 'bg-green-500' :
                                               ($audit->status === 'partial' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                            {{ ucfirst($audit->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $audit->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-600">No audit history</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- OpenSpec Display -->
            <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Specification</h2>
                </div>
                <div class="px-6 py-4">
                    <pre class="bg-gray-50 p-4 rounded text-xs overflow-x-auto text-gray-700">{{ $project->spec_content }}</pre>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

<x-ui::app-layout>
    <x-slot name="title">SpecGuard AI - Dashboard</x-slot>

    <!-- Scripts and Styles for Mermaid -->
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
    <script>
        mermaid.initialize({ startOnLoad: true, theme: 'dark' });
    </script>
    <style>
        .mermaid svg { max-height: 400px; }
        .node-complete { fill: rgba(16, 185, 129, 0.2) !important; stroke: rgba(16, 185, 129, 0.5) !important; }
        .node-partial { fill: rgba(245, 158, 11, 0.2) !important; stroke: rgba(245, 158, 11, 0.5) !important; }
        .node-missing { fill: rgba(239, 68, 68, 0.2) !important; stroke: rgba(239, 68, 68, 0.5) !important; }
        .node-empty { fill: rgba(55, 65, 81, 0.5) !important; stroke: rgba(75, 85, 99, 0.5) !important; }
    </style>

    <!-- Top Navbar / Header -->
    <header class="h-16 border-b border-[#2A2A2A] flex items-center justify-between px-8 flex-shrink-0 bg-[#121212]/80 backdrop-blur-md sticky top-0 z-10">
        <div class="flex items-center gap-6 h-full">
            <div class="flex items-center gap-2">
                <span class="text-white text-lg font-semibold tracking-tight">Project Overview</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="text-gray-400 hover:text-white transition-colors relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </button>
            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 border border-[#333] ml-2"></div>
        </div>
    </header>

    <!-- Main Workspace -->
    <div class="flex-1 overflow-auto p-8 relative z-0">
        <div class="max-w-7xl mx-auto flex flex-col gap-6">

            @if($projects->isEmpty())
                <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl p-8 flex flex-col items-center justify-center text-center shadow-sm">
                    <div class="w-16 h-16 bg-[#252525] rounded-full flex items-center justify-center mb-4 border border-[#333]">
                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h2 class="text-lg font-semibold text-white mb-2">No projects found</h2>
                    <p class="text-sm text-gray-400 max-w-md">Get started by creating a new project to monitor and audit your specifications automatically.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6">
                    @foreach($projects as $project)
                        <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl flex flex-col overflow-hidden shadow-sm relative">
                            
                            <!-- Card Header -->
                            <div class="px-6 py-5 border-b border-[#2A2A2A] bg-[#121212] flex justify-between items-center">
                                <div>
                                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                        {{ $project->name }}
                                    </h2>
                                    <p class="text-xs text-gray-400 mt-1.5 font-mono">{{ $project->repo_url }}</p>
                                </div>
                                <a href="{{ route('project.show', $project) }}" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-500 transition-colors shadow-[0_0_15px_rgba(79,70,229,0.2)] flex items-center gap-2">
                                    View Details
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>

                            <!-- Card Body -->
                            <div class="p-6">
                                @if($project->audits->isNotEmpty())
                                    @php
                                        $latestAudit = $project->audits->first();
                                    @endphp
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                        
                                        <!-- Health Score -->
                                        <div class="bg-[#121212] border border-[#2A2A2A] rounded-lg p-4 relative overflow-hidden">
                                            <div class="absolute top-0 right-0 w-24 h-24 rounded-full blur-xl pointer-events-none -translate-y-1/2 translate-x-1/2 {{ $latestAudit->score >= 80 ? 'bg-emerald-500/10' : ($latestAudit->score >= 50 ? 'bg-yellow-500/10' : 'bg-red-500/10') }}"></div>
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="text-[10px] font-bold tracking-wider text-gray-500 uppercase">Compliance Score</span>
                                                <span class="text-2xl font-bold {{ $latestAudit->score >= 80 ? 'text-emerald-400' : ($latestAudit->score >= 50 ? 'text-yellow-400' : 'text-red-400') }}">{{ $latestAudit->score }}%</span>
                                            </div>
                                            <div class="w-full bg-[#252525] rounded-full h-1.5 overflow-hidden">
                                                <div class="{{ $latestAudit->score >= 80 ? 'bg-emerald-500' : ($latestAudit->score >= 50 ? 'bg-yellow-500' : 'bg-red-500') }} h-full rounded-full shadow-[0_0_10px_currentColor]"
                                                     style="width: {{ $latestAudit->score }}%"></div>
                                            </div>
                                        </div>

                                        <!-- Status & Commit -->
                                        <div class="bg-[#121212] border border-[#2A2A2A] rounded-lg p-4 flex flex-col justify-center gap-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500 font-medium">Status:</span>
                                                <span class="px-2.5 py-1 rounded text-[10px] font-bold tracking-wider uppercase border {{ $latestAudit->status === 'complete' ? 'bg-emerald-900/30 text-emerald-400 border-emerald-500/30' : ($latestAudit->status === 'partial' ? 'bg-yellow-900/30 text-yellow-400 border-yellow-500/30' : 'bg-red-900/30 text-red-400 border-red-500/30') }}">
                                                    {{ $latestAudit->status }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500 font-medium">Commit:</span>
                                                <div class="flex items-center gap-1.5 px-2 py-1 rounded bg-[#1E1E1E] border border-[#333] text-gray-300">
                                                    <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002-2h8a2 2 0 002-2v-2"></path></svg>
                                                    <span class="font-mono text-xs">{{ substr($latestAudit->commit_hash, 0, 8) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <!-- Mermaid Visualization -->
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-200 mb-3 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                                Workflow Map
                                            </h4>
                                            <div class="p-4 bg-[#0A0A0A] border border-[#2A2A2A] rounded-lg overflow-x-auto flex items-center justify-center min-h-[200px]">
                                                <div class="mermaid text-sm" id="mermaid-{{ $project->id }}">
                                                    graph TD
                                                    A[Authentication]
                                                    B[Validation]
                                                    C[Logging]

                                                    A --> B
                                                    B --> C
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Details & Missing Reqs -->
                                        @if($latestAudit->result_json)
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-200 mb-3 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                    Audit Details
                                                </h4>
                                                @if(isset($latestAudit->result_json['missing_requirements']) && count($latestAudit->result_json['missing_requirements']) > 0)
                                                    <div class="bg-red-900/10 border border-red-900/30 p-4 rounded-lg">
                                                        <div class="flex items-center gap-2 mb-3">
                                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                            <p class="text-red-400 font-semibold text-xs tracking-wide uppercase">Missing Requirements:</p>
                                                        </div>
                                                        <ul class="space-y-2">
                                                            @foreach($latestAudit->result_json['missing_requirements'] as $req)
                                                                <li class="flex items-start gap-2 text-sm text-red-300/80">
                                                                    <span class="mt-1.5 w-1 h-1 rounded-full bg-red-500 flex-shrink-0"></span>
                                                                    {{ $req }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @else
                                                    <div class="bg-emerald-900/10 border border-emerald-900/30 p-4 rounded-lg flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-emerald-900/50 flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </div>
                                                        <p class="text-sm text-emerald-400">All specification requirements have been met in this commit.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="py-12 flex flex-col items-center justify-center text-center">
                                        <div class="w-12 h-12 bg-[#1A1A1A] rounded-full border border-[#333] flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        </div>
                                        <p class="text-sm text-gray-400 font-medium">No audits recorded yet.</p>
                                        <p class="text-xs text-gray-500 mt-1">Push code to the GitHub repository to trigger the first compliance audit.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Footer -->
                            <div class="px-6 py-3 bg-[#121212] border-t border-[#2A2A2A] text-xs text-gray-500 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Last updated: <span class="text-gray-400">{{ $project->audits->first()?->created_at?->diffForHumans() ?? 'Never' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <!-- Scripts -->
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
</x-ui::app-layout>

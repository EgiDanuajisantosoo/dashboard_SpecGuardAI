<x-app-layout>
    <x-slot name="title">Live Audit Board - SpecGuard AI</x-slot>

    <!-- Top Navbar -->
    <header class="h-16 border-b border-[#2A2A2A] flex items-center justify-between px-8 flex-shrink-0 bg-[#121212]/80 backdrop-blur-md sticky top-0 z-10">
        <div class="flex items-center gap-6 h-full">
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm font-medium">SpecGuard</span>
                <span class="text-gray-600">/</span>
                <span class="text-white text-sm font-semibold">{{ $project->name }}</span>
            </div>
            <div class="h-4 w-px bg-[#2A2A2A]"></div>
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-xs text-green-500 font-bold tracking-wide uppercase">Webhook Active</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-300 border border-[#333] rounded-md hover:bg-[#1A1A1A] transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                GitHub
            </button>
            <button class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 border border-[#333] ml-2"></div>
        </div>
    </header>

    <!-- Workspace -->
    <div class="flex-1 overflow-auto p-8 relative z-0">
        <div class="max-w-7xl mx-auto flex flex-col gap-6">

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Health Score -->
                <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl p-5 flex flex-col justify-between shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 blur-2xl rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="text-[10px] font-bold tracking-wider text-gray-500 uppercase">Health Score</h3>
                            <p class="text-xs text-gray-400 mt-1">Overall Requirement Coverage</p>
                        </div>
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 3V10m0 0l-4-4m4 4l4-4m-4 17a9 9 0 110-18 9 9 0 010 18z"></path></svg>
                    </div>
                    <div class="flex items-end gap-3 mt-4">
                        <span class="text-4xl font-bold text-white tracking-tight">{{ $latestAudit->score ?? 0 }}%</span>
                        <div class="flex items-center gap-1 text-xs font-medium text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded border border-emerald-500/20 mb-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            2.4%
                        </div>
                    </div>
                </div>

                <!-- Compliance Gaps -->
                <div class="bg-[#161616] border border-red-900/30 rounded-xl p-5 flex flex-col justify-between shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/10 blur-2xl rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="text-[10px] font-bold tracking-wider text-gray-500 uppercase">Compliance Gaps</h3>
                            <p class="text-xs text-gray-400 mt-1">Missing from Implementation</p>
                        </div>
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="flex items-end gap-3 mt-4">
                        <span class="text-4xl font-bold text-red-400 tracking-tight">{{ isset($latestAudit->result_json['missing_requirements']) ? count($latestAudit->result_json['missing_requirements']) : 0 }}</span>
                    </div>
                </div>

                <!-- Audited Commits -->
                <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl p-5 flex flex-col justify-between shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="text-[10px] font-bold tracking-wider text-gray-500 uppercase">Audited Commits</h3>
                            <p class="text-xs text-gray-400 mt-1">Processed by AI Engine</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="flex items-end gap-3 mt-4">
                        <span class="text-4xl font-bold text-white tracking-tight">{{ $project->audits->count() }}</span>
                    </div>
                </div>

                <!-- AI Prompts -->
                <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl p-5 flex flex-col justify-between shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 blur-2xl rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="text-[10px] font-bold tracking-wider text-gray-500 uppercase">AI Recommendations</h3>
                            <p class="text-xs text-gray-400 mt-1">Prompts generated for developers</p>
                        </div>
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="flex items-end gap-3 mt-4">
                        <span class="text-4xl font-bold text-white tracking-tight">2</span>
                    </div>
                </div>
            </div>

            <!-- Workflow Compliance Map (Mermaid Mock) -->
            <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl p-6 shadow-sm overflow-hidden flex flex-col relative">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-white">Workflow Compliance Map</h2>
                        <p class="text-sm text-gray-400">Real-time dependency and requirement trace.</p>
                    </div>
                    <div class="flex items-center gap-4 text-[10px] font-mono text-gray-400 uppercase tracking-wider bg-[#121212] px-3 py-1.5 border border-[#2A2A2A] rounded-md">
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Complete</div>
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-yellow-500"></span> Partial</div>
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Missing</div>
                    </div>
                </div>

                <!-- Flowchart Visualization Area -->
                <div class="relative h-[280px] bg-[#0A0A0A] rounded-lg border border-[#2A2A2A] flex items-center justify-center p-8 overflow-auto">
                    <div class="mermaid">
                        graph TD
                        A[Authentication]
                        B[Validation]
                        C[Logging]

                        A --> B
                        B --> C
                    </div>
                </div>
            </div>

            <!-- Bottom Two Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Recent Activity -->
                <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl flex flex-col overflow-hidden">
                    <div class="h-12 border-b border-[#2A2A2A] px-5 flex items-center justify-between bg-[#121212]">
                        <div class="flex items-center gap-2 text-white font-medium">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002-2h8a2 2 0 002-2v-2"></path></svg>
                            Recent Commits
                        </div>
                        <button class="text-[11px] font-bold tracking-wider text-gray-500 uppercase hover:text-gray-300 transition-colors">View All</button>
                    </div>
                    <div class="p-5 flex flex-col gap-4">
                        @forelse($project->audits->sortByDesc('created_at')->take(5) as $audit)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#252525] border border-[#333] flex items-center justify-center flex-shrink-0">
                                @if($audit->status === 'complete')
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @elseif($audit->status === 'partial')
                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 17h.01"></path></svg>
                                @else
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-0.5">
                                    <h4 class="text-sm font-medium text-gray-200 truncate">Commit {{ substr($audit->commit_hash, 0, 8) }}</h4>
                                    <span class="text-xs text-gray-500 flex-shrink-0">{{ $audit->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-400 mb-2">Score: {{ $audit->score }}%</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono border {{ $audit->status === 'complete' ? 'border-emerald-900/50 bg-emerald-900/20 text-emerald-400' : ($audit->status === 'partial' ? 'border-yellow-900/50 bg-yellow-900/20 text-yellow-400' : 'border-red-900/50 bg-red-900/20 text-red-400') }}">Status: {{ strtoupper($audit->status) }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-sm text-gray-500 text-center py-4">No recent commits.</div>
                        @endforelse
                    </div>
                </div>

                <!-- AI Audit Insights -->
                <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl flex flex-col overflow-hidden">
                    <div class="h-12 border-b border-[#2A2A2A] px-5 flex items-center gap-2 bg-[#121212]">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        <span class="text-white font-medium">AI Audit Insights &amp; Prompts</span>
                    </div>
                    <div class="p-5 flex flex-col gap-4">
                        <p class="text-sm text-gray-400 mb-1">SpecGuard detected missing implementations based on current branch state. Use these AI prompts to resolve them.</p>
                        
                        <!-- Prompt Card 1 -->
                        <div class="border border-red-900/30 bg-[#1A1112] rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-sm font-semibold text-red-400">Missing Activity Logging</h4>
                                <span class="text-[9px] font-bold tracking-wider text-red-500 border border-red-500/30 px-1.5 py-0.5 rounded uppercase">Critical</span>
                            </div>
                            <p class="text-xs text-red-300/70 mb-3">Login controller lacks `Log::info()` or `Log::warning()` implementations specified in OpenSpec.</p>
                            
                            <div class="bg-[#121212] border border-[#333] rounded p-3 relative group">
                                <p class="text-xs font-mono text-gray-300 pr-8">Add Log::warning() for failed login attempts in AuthController and Log::info() for successful authentications.</p>
                                <button class="absolute top-2 right-2 p-1.5 rounded bg-[#252525] text-gray-400 opacity-0 group-hover:opacity-100 hover:text-white transition-all border border-[#333] hover:border-indigo-500/50" title="Copy Prompt">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
    <script>
        mermaid.initialize({ startOnLoad: true, theme: 'dark' });
    </script>
    @if($latestAudit && isset($latestAudit->result_json['node_status']))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const nodeStatus = @json($latestAudit->result_json['node_status']);
                const svg = document.querySelector('.mermaid svg');
                if (!svg) return;

                for (const [node, status] of Object.entries(nodeStatus)) {
                    // A simple workaround is finding nodes containing the text.
                    const allNodes = svg.querySelectorAll('.node');
                    allNodes.forEach(el => {
                        const text = el.textContent.trim().toLowerCase();
                        if (text === node.toLowerCase()) {
                            el.style.fill = status ? '#10b981' : '#ef4444'; // Green or Red
                            const rect = el.querySelector('rect, circle, polygon');
                            if(rect) rect.style.fill = status ? '#10b981' : '#ef4444';
                        }
                    });
                }
            }, 500);
        });
    </script>
    @endif
</x-app-layout>

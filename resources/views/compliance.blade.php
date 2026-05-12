<x-app-layout>
    <x-slot name="title">Compliance Map - SpecGuard AI</x-slot>

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
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-300 border border-[#333] rounded-md hover:bg-[#1A1A1A] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            @if($project->repo_url)
            <a href="{{ $project->repo_url }}" target="_blank" class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-300 border border-[#333] rounded-md hover:bg-[#1A1A1A] transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                GitHub
            </a>
            @endif
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
                        @if($latestAudit && $latestAudit->score >= 80)
                        <span class="text-xs font-medium text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded border border-emerald-500/20 mb-1">Good</span>
                        @elseif($latestAudit && $latestAudit->score >= 50)
                        <span class="text-xs font-medium text-yellow-400 bg-yellow-400/10 px-2 py-0.5 rounded border border-yellow-500/20 mb-1">Partial</span>
                        @else
                        <span class="text-xs font-medium text-red-400 bg-red-400/10 px-2 py-0.5 rounded border border-red-500/20 mb-1">{{ $latestAudit ? 'Low' : 'N/A' }}</span>
                        @endif
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
                        <span class="text-4xl font-bold text-red-400 tracking-tight">
                            @if($latestAudit && isset($latestAudit->result_json['missing_requirements']))
                                {{ count($latestAudit->result_json['missing_requirements']) }}
                            @else
                                0
                            @endif
                        </span>
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

                <!-- AI Recommendations -->
                <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl p-5 flex flex-col justify-between shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 blur-2xl rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="text-[10px] font-bold tracking-wider text-gray-500 uppercase">AI Recommendations</h3>
                            <p class="text-xs text-gray-400 mt-1">Issues detected for developers</p>
                        </div>
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="flex items-end gap-3 mt-4">
                        <span class="text-4xl font-bold text-white tracking-tight">
                            @if($latestAudit && isset($latestAudit->result_json['missing_requirements']))
                                {{ count($latestAudit->result_json['missing_requirements']) }}
                            @else
                                0
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Workflow Compliance Map (Mermaid) -->
            <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl p-6 shadow-sm overflow-hidden flex flex-col relative">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-white">Workflow Compliance Map</h2>
                        <p class="text-sm text-gray-400">Real-time dependency and requirement trace. Node colors reflect AI audit results.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Zoom Controls -->
                        <div class="flex items-center gap-1">
                            <button onclick="cmZoomIn()" class="px-2 py-1 text-xs text-gray-400 border border-[#333] rounded hover:bg-[#252525] transition-colors">＋</button>
                            <button onclick="cmZoomOut()" class="px-2 py-1 text-xs text-gray-400 border border-[#333] rounded hover:bg-[#252525] transition-colors">－</button>
                            <button onclick="cmResetZoom()" class="px-2 py-1 text-xs text-gray-400 border border-[#333] rounded hover:bg-[#252525] transition-colors">⟳</button>
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-mono text-gray-400 uppercase tracking-wider bg-[#121212] px-3 py-1.5 border border-[#2A2A2A] rounded-md">
                            <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Implemented</div>
                            <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Missing / Broken</div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Flowchart Visualization -->
                <div class="relative bg-[#0A0A0A] rounded-lg border border-[#2A2A2A] flex items-center justify-center overflow-auto" id="cm-container" style="min-height: 560px;">
                    @if($project->spec_content)
                        <div id="cm-wrapper" style="transform-origin: center; transition: transform 0.2s ease; padding: 32px;">
                            <div class="mermaid" id="compliance-mermaid" style="font-size: 15px;">
                                {!! $project->spec_content !!}
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm italic">No specification has been generated for this project yet.</p>
                    @endif
                </div>

                @if($latestAudit && isset($latestAudit->result_json['summary']))
                <p class="text-xs text-gray-500 mt-3 italic">AI Summary: {{ $latestAudit->result_json['summary'] }}</p>
                @endif
            </div>

            <!-- Bottom Two Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Recent Commits -->
                <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl flex flex-col overflow-hidden">
                    <div class="h-12 border-b border-[#2A2A2A] px-5 flex items-center justify-between bg-[#121212]">
                        <div class="flex items-center gap-2 text-white font-medium">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                            Recent Commits
                        </div>
                    </div>
                    <div class="p-5 flex flex-col gap-4">
                        @forelse($project->audits->sortByDesc('created_at')->take(5) as $audit)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#252525] border border-[#333] flex items-center justify-center flex-shrink-0">
                                @if($audit->status === 'complete')
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @elseif($audit->status === 'partial')
                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path></svg>
                                @else
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-0.5">
                                    <h4 class="text-sm font-medium text-gray-200 truncate font-mono">{{ substr($audit->commit_hash, 0, 10) }}</h4>
                                    <span class="text-xs text-gray-500 flex-shrink-0">{{ $audit->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-400 mb-2">Score: <span class="font-bold {{ $audit->score >= 80 ? 'text-emerald-400' : ($audit->score >= 50 ? 'text-yellow-400' : 'text-red-400') }}">{{ $audit->score }}%</span></p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono border {{ $audit->status === 'complete' ? 'border-emerald-900/50 bg-emerald-900/20 text-emerald-400' : ($audit->status === 'partial' ? 'border-yellow-900/50 bg-yellow-900/20 text-yellow-400' : 'border-red-900/50 bg-red-900/20 text-red-400') }}">Status: {{ strtoupper($audit->status) }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-sm text-gray-500 text-center py-8">
                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            No commits audited yet. Push to GitHub to trigger an audit.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- AI Audit Insights — Feature Status Panel -->
                <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl flex flex-col overflow-hidden">
                    <div class="h-12 border-b border-[#2A2A2A] px-5 flex items-center gap-2 bg-[#121212]">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        <span class="text-white font-medium">Feature Status</span>
                        @if($latestAudit)
                        <span class="ml-auto text-[10px] font-mono px-2 py-0.5 rounded border
                            {{ ($latestAudit->status ?? 'failed') === 'complete' ? 'border-emerald-800/50 bg-emerald-900/20 text-emerald-400' :
                               (($latestAudit->status ?? 'failed') === 'partial' ? 'border-yellow-800/50 bg-yellow-900/20 text-yellow-400' :
                               'border-red-800/50 bg-red-900/20 text-red-400') }}">
                            Score: {{ $latestAudit->score ?? 0 }}%
                        </span>
                        @endif
                    </div>
                    <div class="p-5 flex flex-col gap-3 overflow-auto flex-1">
                    @if($latestAudit)
                        @php
                            $rj = $latestAudit->result_json ?? [];
                            $implemented  = $rj['implemented']   ?? [];
                            $missing      = $rj['missing']       ?? [];
                            $qualityNotes = $rj['quality_notes'] ?? [];
                            $missingRequirements = $rj['missing_requirements'] ?? [];

                            // If old node_status format, derive lists
                            if (empty($implemented) && empty($missing) && !empty($rj['node_status'])) {
                                foreach ($rj['node_status'] as $node => $val) {
                                    if ($val === true) $implemented[] = $node;
                                    else $missing[] = $node;
                                }
                            }

                            // Build dynamic feature list from AI audit result
                            $partial = $rj['partial'] ?? [];

                            // Merge all features the AI detected (from any category)
                            $allDetected = array_unique(array_merge($implemented, $partial, $missing));

                            // Build $allFeatures as key => readable label (snake_case → Title Case)
                            $allFeatures = [];
                            foreach ($allDetected as $feat) {
                                $allFeatures[$feat] = ucwords(str_replace('_', ' ', $feat));
                            }

                            // If no features detected yet, show a placeholder
                            if (empty($allFeatures)) {
                                $allFeatures = ['no_data' => 'No Audit Data'];
                            }

                            // Determine per-feature status
                            $featureStatuses = [];
                            foreach ($allFeatures as $key => $label) {
                                if (in_array($key, $implemented)) {
                                    $featureStatuses[$key] = 'implemented';
                                } elseif (in_array($key, $partial)) {
                                    $featureStatuses[$key] = 'partial';
                                } elseif (in_array($key, $missing)) {
                                    $featureStatuses[$key] = 'missing';
                                } else {
                                    $featureStatuses[$key] = 'unknown';
                                }
                            }
                        @endphp

                        {{-- Feature Status Cards --}}
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($allFeatures as $key => $label)
                            @php
                                $status = $featureStatuses[$key] ?? 'unknown';
                                $colors = match($status) {
                                    'implemented' => [
                                        'border' => 'border-emerald-900/40',
                                        'bg'     => 'bg-emerald-950/40',
                                        'dot'    => 'bg-emerald-500',
                                        'text'   => 'text-emerald-400',
                                        'badge'  => 'bg-emerald-900/30 text-emerald-400 border-emerald-800/40',
                                        'label'  => '✓ Terimplementasi',
                                        'note'   => 'Fitur sudah ada dan sesuai dengan PRD.',
                                    ],
                                    'partial' => [
                                        'border' => 'border-yellow-900/40',
                                        'bg'     => 'bg-yellow-950/40',
                                        'dot'    => 'bg-yellow-500 animate-pulse',
                                        'text'   => 'text-yellow-400',
                                        'badge'  => 'bg-yellow-900/30 text-yellow-400 border-yellow-800/40',
                                        'label'  => '⚠ Tidak Sesuai PRD',
                                        'note'   => 'Fitur ada tapi belum sepenuhnya sesuai dengan spesifikasi PRD.',
                                    ],
                                    'missing' => [
                                        'border' => 'border-red-900/40',
                                        'bg'     => 'bg-red-950/40',
                                        'dot'    => 'bg-red-500 animate-pulse',
                                        'text'   => 'text-red-400',
                                        'badge'  => 'bg-red-900/30 text-red-400 border-red-800/40',
                                        'label'  => '✗ Belum Terimplementasi',
                                        'note'   => 'Fitur ini belum ditemukan dalam kodebase.',
                                    ],
                                    default => [
                                        'border' => 'border-gray-800/40',
                                        'bg'     => 'bg-gray-900/20',
                                        'dot'    => 'bg-gray-600',
                                        'text'   => 'text-gray-400',
                                        'badge'  => 'bg-gray-800/30 text-gray-400 border-gray-700/40',
                                        'label'  => '? Tidak Diketahui',
                                        'note'   => 'Belum ada data audit untuk fitur ini.',
                                    ],
                                };
                            @endphp
                            <div class="flex items-center gap-3 border {{ $colors['border'] }} {{ $colors['bg'] }} rounded-lg px-4 py-3">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $colors['dot'] }}"></span>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-sm font-medium text-gray-200">{{ $label }}</span>
                                        <span class="text-[10px] font-mono px-2 py-0.5 rounded border {{ $colors['badge'] }} flex-shrink-0">{{ $colors['label'] }}</span>
                                    </div>
                                    <p class="text-xs {{ $colors['text'] }} mt-0.5 opacity-80">{{ $colors['note'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Quality Notes --}}
                        @if(!empty($qualityNotes))
                        <div class="border border-indigo-900/30 bg-[#11121A] rounded-lg p-4 mt-1">
                            <h4 class="text-xs font-semibold text-indigo-400 mb-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Catatan Kualitas Kode
                            </h4>
                            <ul class="space-y-1.5">
                                @foreach($qualityNotes as $note)
                                <li class="text-xs text-indigo-300/80 flex gap-2">
                                    <span class="text-indigo-500 flex-shrink-0">›</span>
                                    {{ $note }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Audit Error --}}
                        @if(isset($rj['error']))
                        <div class="border border-yellow-900/30 bg-[#1A1A12] rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-yellow-400 mb-2">Audit Error</h4>
                            <p class="text-xs text-yellow-300/80 font-mono">{{ $rj['error'] }}</p>
                        </div>
                        @endif

                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            <p class="text-sm text-gray-500">No audit results yet.</p>
                            <p class="text-xs text-gray-600 mt-1">Push code to GitHub to trigger an AI compliance audit.</p>
                        </div>
                    @endif
                    </div>
                </div>

        </div>
    </div>

    <!-- Node Coloring Script — colors flowchart nodes based on audit result -->
    @if($latestAudit)
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Data from PHP ──────────────────────────────────────────────
            const implemented  = @json($latestAudit->result_json['implemented']  ?? []);
            const missing      = @json($latestAudit->result_json['missing']      ?? []);
            const qualityNotes = @json($latestAudit->result_json['quality_notes'] ?? []);
            const nodeStatus   = @json($latestAudit->result_json['node_status']  ?? (object)[]);

            // ── Keyword map: feature key → words that appear in flowchart node labels ─
            const featureKeywords = {
                registration : ['registr', 'daftar', 'register', 'signup', 'sign up', 'hash password', 'simpan data'],
                login        : ['login', 'masuk', 'sign in', 'signin', 'cocok', 'email & password'],
                session_guard: ['sesi', 'session', 'cek sesi', 'guard', 'belum login', 'token'],
                home_page    : ['beranda', 'home', 'dashboard', 'selamat datang', 'welcome'],
                logout       : ['logout', 'keluar', 'hapus sesi', 'clear session', 'signout'],
                error_handling: ['error', 'salah', 'gagal', 'pesan', 'message', 'failed'],
            };

            // ── Determine per-feature status ───────────────────────────────
            // Green = implemented, Yellow = implemented but quality issues, Red = missing
            function getFeatureStatus(featKey) {
                const isImpl    = implemented.includes(featKey);
                const isMissing = missing.includes(featKey);
                const noteStr   = qualityNotes.join(' ').toLowerCase();
                const hasIssue  = isImpl && (
                    noteStr.includes(featKey.replace('_', ' ')) ||
                    noteStr.includes(featureKeywords[featKey]?.[0] ?? '@@')
                );

                if (isMissing)  return 'missing';
                if (hasIssue)   return 'partial';
                if (isImpl)     return 'implemented';
                return 'unknown';
            }

            // ── Build a flat label→status lookup for fast node matching ────
            const labelStatusMap = {};

            // From new format: implemented/missing arrays
            for (const [featKey, keywords] of Object.entries(featureKeywords)) {
                const status = getFeatureStatus(featKey);
                for (const kw of keywords) {
                    labelStatusMap[kw.toLowerCase()] = status;
                }
            }

            // From old format: node_status object (individual node labels)
            for (const [label, isOk] of Object.entries(nodeStatus)) {
                labelStatusMap[label.toLowerCase()] = isOk ? 'implemented' : 'missing';
            }

            // ── Color definitions ─────────────────────────────────────────
            const colors = {
                implemented : { fill: '#064e3b', stroke: '#10b981', strokeWidth: '2px', text: '#6ee7b7' },
                partial     : { fill: '#451a03', stroke: '#f59e0b', strokeWidth: '2px', text: '#fcd34d' },
                missing     : { fill: '#450a0a', stroke: '#ef4444', strokeWidth: '2px', text: '#fca5a5' },
                unknown     : null,
            };

            // ── Apply colors to SVG nodes ─────────────────────────────────
            function applyColors() {
                const container = document.getElementById('compliance-mermaid');
                if (!container) return;

                const svg = container.querySelector('svg');
                if (!svg) { setTimeout(applyColors, 400); return; }

                const allNodes = svg.querySelectorAll('.node');
                if (allNodes.length === 0) { setTimeout(applyColors, 400); return; }

                allNodes.forEach(nodeEl => {
                    // Get the visible text inside this SVG node
                    const spans = nodeEl.querySelectorAll('span, p, div, foreignObject');
                    let nodeText = '';
                    if (spans.length > 0) {
                        nodeText = spans[0].textContent.trim().toLowerCase();
                    } else {
                        const textEls = nodeEl.querySelectorAll('text');
                        nodeText = Array.from(textEls).map(t => t.textContent).join(' ').trim().toLowerCase();
                    }

                    if (!nodeText) return;

                    // Find best matching status
                    let matchedStatus = null;
                    let bestMatchLen = 0;
                    for (const [kw, status] of Object.entries(labelStatusMap)) {
                        if (nodeText.includes(kw) && kw.length > bestMatchLen) {
                            matchedStatus = status;
                            bestMatchLen = kw.length;
                        }
                    }

                    if (!matchedStatus || !colors[matchedStatus]) return;

                    const c = colors[matchedStatus];

                    // Color all shape elements inside the node
                    const shapes = nodeEl.querySelectorAll('rect, circle, polygon, ellipse, path');
                    shapes.forEach(shape => {
                        // Skip arrow paths (they have no fill or marker-end)
                        if (shape.getAttribute('marker-end')) return;
                        shape.style.fill        = c.fill;
                        shape.style.stroke      = c.stroke;
                        shape.style.strokeWidth = c.strokeWidth;
                        shape.style.transition  = 'all 0.4s ease';
                    });

                    // Color text inside the node
                    const textEls = nodeEl.querySelectorAll('span, text, p, div');
                    textEls.forEach(t => {
                        t.style.color = c.text;
                        t.style.fill  = c.text;
                    });
                });
            }

            // Run after Mermaid renders (it uses requestAnimationFrame internally)
            setTimeout(applyColors, 900);
            // Retry once more in case of slow render
            setTimeout(applyColors, 2000);
        });
    </script>
    @endif

    <!-- Scale up SVG nodes after Mermaid renders -->
    <script>
        function scaleMermaidNodes() {
            const container = document.getElementById('compliance-mermaid');
            if (!container) return;
            const svg = container.querySelector('svg');
            if (!svg) { setTimeout(scaleMermaidNodes, 400); return; }

            // Scale the entire SVG up so nodes appear larger
            const currentW = svg.getAttribute('width')  || svg.viewBox.baseVal.width  || 800;
            const currentH = svg.getAttribute('height') || svg.viewBox.baseVal.height || 400;
            const scale = 1.4;

            svg.style.width  = (parseFloat(currentW) * scale) + 'px';
            svg.style.height = (parseFloat(currentH) * scale) + 'px';
            svg.style.maxWidth = '100%';

            // Also bump up rect/shape padding by making them taller
            const rects = svg.querySelectorAll('.node rect');
            rects.forEach(r => {
                const h = parseFloat(r.getAttribute('height') || 0);
                if (h > 0 && h < 60) {
                    r.setAttribute('height', h * 1.3);
                    r.setAttribute('y', parseFloat(r.getAttribute('y') || 0) - (h * 0.15));
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(scaleMermaidNodes, 1000);
            setTimeout(scaleMermaidNodes, 2200);
        });
    </script>

    <!-- Compliance Map Zoom Controls -->
    <script>
        let cmScale = 1;
        const cmWrapper = document.getElementById('cm-wrapper');
        const cmContainer = document.getElementById('cm-container');

        function cmZoomIn() {
            cmScale = Math.min(cmScale + 0.2, 3);
            applyCmZoom();
        }
        function cmZoomOut() {
            cmScale = Math.max(cmScale - 0.2, 0.3);
            applyCmZoom();
        }
        function cmResetZoom() {
            cmScale = 1;
            applyCmZoom();
        }
        function applyCmZoom() {
            if (cmWrapper) cmWrapper.style.transform = `scale(${cmScale})`;
        }

        if (cmContainer) {
            cmContainer.addEventListener('wheel', function(e) {
                e.preventDefault();
                if (e.deltaY < 0) cmZoomIn();
                else cmZoomOut();
            }, { passive: false });
        }
    </script>

</x-app-layout>

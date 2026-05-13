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

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Column: Workflow Compliance Map (Mermaid) -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl p-6 shadow-sm overflow-hidden flex flex-col relative h-full">
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
                        <div class="relative bg-[#0A0A0A] rounded-lg border border-[#2A2A2A] flex-1 flex items-center justify-center overflow-auto" id="cm-container" style="min-height: 600px;">
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
                </div>

                <!-- Right Column: Info Stack -->
                <div class="lg:col-span-4 flex flex-col gap-6">
                    
                    {{-- 1. AI Fix Prompt Panel --}}
                    @if($latestAudit && !empty($latestAudit->result_json) && !isset($latestAudit->result_json['error']))
                        @php
                            $rj_prompt = $latestAudit->result_json;
                            $auditPartial  = $rj_prompt['partial']       ?? [];
                            $auditMissing  = $rj_prompt['missing']       ?? [];
                            $auditReviews  = $rj_prompt['reviews']       ?? [];
                            $auditSummary  = $rj_prompt['summary']       ?? '';
                            $prdSnippet    = \Illuminate\Support\Str::limit($project->prd_content ?? '', 600, '...');

                            $promptLines   = [];
                            $promptLines[] = "You are a senior software engineer. Fix ALL compliance issues in this codebase based on the audit report below.";
                            $promptLines[] = "";
                            $promptLines[] = "## Project: {$project->name}";
                            $promptLines[] = "## Audit Summary: {$auditSummary}";
                            $promptLines[] = "## PRD Excerpt:";
                            $promptLines[] = $prdSnippet;
                            $promptLines[] = "";
                            $promptLines[] = "---";
                            $promptLines[] = "## Issues To Fix:";
                            $promptLines[] = "";

                            if (!empty($auditPartial)) {
                                $promptLines[] = "### ⚠ PARTIAL — Feature exists but does not fully match PRD:";
                                foreach ($auditPartial as $feat) {
                                    $label = ucwords(str_replace('_', ' ', $feat));
                                    $r = $auditReviews[$feat] ?? null;
                                    $promptLines[] = "";
                                    $promptLines[] = "**{$label}**";
                                    if ($r) {
                                        if (!empty($r['found']))          $promptLines[] = "- Found: {$r['found']}";
                                        if (!empty($r['issue']))          $promptLines[] = "- Issue: {$r['issue']}";
                                        if (!empty($r['recommendation'])) $promptLines[] = "- Fix: {$r['recommendation']}";
                                    }
                                }
                                $promptLines[] = "";
                            }

                            if (!empty($auditMissing)) {
                                $promptLines[] = "### ✗ MISSING — Feature not found in codebase at all:";
                                foreach ($auditMissing as $feat) {
                                    $label = ucwords(str_replace('_', ' ', $feat));
                                    $r = $auditReviews[$feat] ?? null;
                                    $promptLines[] = "";
                                    $promptLines[] = "**{$label}**";
                                    if ($r) {
                                        if (!empty($r['issue']))          $promptLines[] = "- PRD requires: {$r['issue']}";
                                        if (!empty($r['recommendation'])) $promptLines[] = "- Implementation: {$r['recommendation']}";
                                        if (!empty($r['priority']))       $promptLines[] = "- Priority: {$r['priority']}";
                                    } else {
                                        $promptLines[] = "- This feature is completely missing. Implement it according to the PRD.";
                                    }
                                }
                                $promptLines[] = "";
                            }

                            if (!empty($rj_prompt['quality_notes'])) {
                                $promptLines[] = "### 📋 General Quality Issues:";
                                foreach ($rj_prompt['quality_notes'] as $note) {
                                    $promptLines[] = "- {$note}";
                                }
                                $promptLines[] = "";
                            }

                            $promptLines[] = "---";
                            $promptLines[] = "## Instructions:";
                            $promptLines[] = "1. Fix ALL partial features to fully match PRD specification.";
                            $promptLines[] = "2. Implement ALL missing features from scratch.";
                            $promptLines[] = "3. Follow existing code style and architecture conventions.";
                            $promptLines[] = "4. Add proper validation, error handling, and security where missing.";
                            $promptLines[] = "5. Show the complete file content for each changed file.";

                            $aiPrompt = implode("\n", $promptLines);
                            $hasIssues = !empty($auditPartial) || !empty($auditMissing);
                        @endphp

                        @if($hasIssues)
                        <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl overflow-hidden shadow-sm flex flex-col">
                            <div class="h-12 border-b border-[#2A2A2A] px-5 flex items-center justify-between bg-[#121212] flex-shrink-0">
                                <div class="flex items-center gap-2 text-white font-medium">
                                    <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                    <span>Rekomendasi Prompt AI</span>
                                </div>
                                <button id="copy-prompt-btn" onclick="copyAiPrompt()"
                                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-violet-600/80 hover:bg-violet-600 border border-violet-500/50 rounded-md transition-all">
                                    <svg id="copy-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    <svg id="check-icon" class="w-3.5 h-3.5 hidden text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span id="copy-btn-text">Copy</span>
                                </button>
                            </div>

                            {{-- Prompt Content --}}
                            <div class="relative flex-1">
                                <textarea id="ai-fix-prompt" readonly
                                    class="w-full bg-[#0D0D0D] text-gray-300 text-[11px] font-mono leading-relaxed p-4 resize-none outline-none border-none focus:ring-0 custom-scrollbar"
                                    style="min-height: 200px;"
                                    onclick="this.select()">{{ $aiPrompt }}</textarea>
                                <div class="pointer-events-none absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-[#0D0D0D] to-transparent"></div>
                            </div>
                        </div>
                        @endif
                    @endif

                    <!-- 2. Recent Commits -->
                    <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl flex flex-col overflow-hidden shadow-sm">
                        <div class="h-12 border-b border-[#2A2A2A] px-5 flex items-center justify-between bg-[#121212]">
                            <div class="flex items-center gap-2 text-white font-medium">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                Recent Commits
                            </div>
                        </div>
                        <div class="p-5 flex flex-col gap-4 bg-[#161616]">
                            @forelse($project->audits->sortByDesc('created_at')->take(3) as $audit)
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
                                    <p class="text-xs text-gray-400">Score: <span class="font-bold {{ $audit->score >= 80 ? 'text-emerald-400' : ($audit->score >= 50 ? 'text-yellow-400' : 'text-red-400') }}">{{ $audit->score }}%</span></p>
                                </div>
                            </div>
                            @empty
                            <p class="text-xs text-gray-500 text-center py-2">No audits yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- 3. Feature Status Panel -->
                    <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl flex flex-col overflow-hidden shadow-sm">
                        <div class="h-12 border-b border-[#2A2A2A] px-5 flex items-center gap-2 bg-[#121212]">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            <span class="text-white font-medium">Feature Status</span>
                        </div>
                        <div class="p-5 flex flex-col gap-3 overflow-auto max-h-[400px] custom-scrollbar bg-[#161616]">
                            @if($latestAudit)
                                @php
                                    $rj_fs = $latestAudit->result_json ?? [];
                                    $fs_implemented  = $rj_fs['implemented']   ?? [];
                                    $fs_missing      = $rj_fs['missing']       ?? [];
                                    $fs_partial      = $rj_fs['partial']       ?? [];
                                    $fs_reviews      = $rj_fs['reviews']       ?? [];

                                    if (empty($fs_implemented) && empty($fs_missing) && !empty($rj_fs['node_status'])) {
                                        foreach ($rj_fs['node_status'] as $node => $val) {
                                            if ($val === true) $fs_implemented[] = $node;
                                            else $fs_missing[] = $node;
                                        }
                                    }

                                    $allDetected = array_unique(array_merge($fs_implemented, $fs_partial, $fs_missing));
                                    $allFeatures = [];
                                    foreach ($allDetected as $feat) {
                                        $allFeatures[$feat] = ucwords(str_replace('_', ' ', $feat));
                                    }
                                    if (empty($allFeatures)) { $allFeatures = ['no_data' => 'No Audit Data']; }

                                    $featureStatuses = [];
                                    foreach ($allFeatures as $key => $label) {
                                        if (in_array($key, $fs_implemented)) $featureStatuses[$key] = 'implemented';
                                        elseif (in_array($key, $fs_partial)) $featureStatuses[$key] = 'partial';
                                        elseif (in_array($key, $fs_missing)) $featureStatuses[$key] = 'missing';
                                        else $featureStatuses[$key] = 'unknown';
                                    }
                                @endphp

                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($allFeatures as $key => $label)
                                    @php
                                        $status = $featureStatuses[$key] ?? 'unknown';
                                        $review = $fs_reviews[$key] ?? null;
                                        $colors = match($status) {
                                            'implemented' => [
                                                'border' => 'border-emerald-900/40', 'bg' => 'bg-emerald-950/40', 'dot' => 'bg-emerald-500', 'text' => 'text-emerald-400',
                                                'badge' => 'bg-emerald-900/30 text-emerald-400 border-emerald-800/40', 'label' => '✓'
                                            ],
                                            'partial' => [
                                                'border' => 'border-yellow-900/40', 'bg' => 'bg-yellow-950/40', 'dot' => 'bg-yellow-500 animate-pulse', 'text' => 'text-yellow-400',
                                                'badge' => 'bg-yellow-900/30 text-yellow-400 border-yellow-800/40', 'label' => '⚠'
                                            ],
                                            'missing' => [
                                                'border' => 'border-red-900/40', 'bg' => 'bg-red-950/40', 'dot' => 'bg-red-500 animate-pulse', 'text' => 'text-red-400',
                                                'badge' => 'bg-red-900/30 text-red-400 border-red-800/40', 'label' => '✗'
                                            ],
                                            default => [
                                                'border' => 'border-gray-800/40', 'bg' => 'bg-gray-900/20', 'dot' => 'bg-gray-600', 'text' => 'text-gray-400',
                                                'badge' => 'bg-gray-800/30 text-gray-400 border-gray-700/40', 'label' => '?'
                                            ],
                                        };
                                    @endphp
                                    <div class="rounded-lg border {{ $colors['border'] }} {{ $colors['bg'] }} p-3">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $colors['dot'] }}"></span>
                                                <span class="text-xs font-medium text-gray-200 truncate">{{ $label }}</span>
                                            </div>
                                            <span class="text-[10px] font-bold {{ $colors['text'] }}">{{ $colors['label'] }}</span>
                                        </div>
                                        @if($review && $status !== 'implemented')
                                            <p class="text-[10px] text-gray-400 line-clamp-2 leading-relaxed">{{ $review['issue'] ?? '' }}</p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-8 text-center">
                                    <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                    <p class="text-sm text-gray-500">No audit results yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Node Coloring Script — colors flowchart nodes based on audit result -->
    @if($latestAudit)
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Data from PHP (all dynamic from AI output) ─────────────────
            const implemented  = @json($latestAudit->result_json['implemented'] ?? []);
            const partial      = @json($latestAudit->result_json['partial']     ?? []);
            const missing      = @json($latestAudit->result_json['missing']     ?? []);
            const nodeStatusOld= @json($latestAudit->result_json['node_status'] ?? (object)[]); // legacy support

            // ── Color definitions ──────────────────────────────────────────
            const colors = {
                implemented : { fill: '#052e16', stroke: '#22c55e', strokeWidth: '2.5px', text: '#86efac' },
                partial     : { fill: '#422006', stroke: '#f59e0b', strokeWidth: '2.5px', text: '#fde68a' },
                missing     : { fill: '#3b0a0a', stroke: '#ef4444', strokeWidth: '2.5px', text: '#fca5a5' },
            };

            /**
             * Convert a snake_case / camelCase feature key to an array of searchable tokens.
             * e.g. "user_registration" → ["user", "registration", "register", "registr", "user registration"]
             */
            function featureToTokens(key) {
                const base  = key.toLowerCase().replace(/_/g, ' ');
                const parts = key.toLowerCase().split('_').filter(p => p.length > 2);
                const tokens = new Set([base, ...parts]);

                // Add common stemming shortcuts
                const stemMap = {
                    'registration' : ['register', 'registr', 'daftar', 'signup', 'sign up'],
                    'register'     : ['registr', 'daftar', 'signup'],
                    'login'        : ['masuk', 'sign in', 'signin', 'log in'],
                    'logout'       : ['keluar', 'log out', 'signout', 'sign out', 'hapus sesi'],
                    'session'      : ['sesi', 'token', 'session'],
                    'session_guard': ['cek sesi', 'sesi', 'guard', 'auth', 'belum login'],
                    'home'         : ['beranda', 'dashboard', 'welcome', 'selamat datang'],
                    'home_page'    : ['beranda', 'halaman utama', 'welcome'],
                    'password'     : ['kata sandi', 'hash', 'bcrypt'],
                    'validation'   : ['validasi', 'valid', 'cek'],
                    'error'        : ['gagal', 'salah', 'pesan error', 'failed'],
                    'error_handling': ['error', 'gagal', 'salah'],
                    'email'        : ['surel', 'email'],
                    'user'         : ['pengguna', 'user'],
                    'create'       : ['buat', 'create', 'tambah'],
                    'redirect'     : ['redirect', 'alihkan', 'halaman'],
                    'product'      : ['produk', 'barang'],
                    'search'       : ['cari', 'search', 'temukan'],
                    'payment'      : ['bayar', 'payment', 'pembayaran'],
                    'cart'         : ['keranjang', 'cart'],
                    'order'        : ['pesanan', 'order'],
                    'profile'      : ['profil', 'akun'],
                    'notification' : ['notif', 'pemberitahuan'],
                    'upload'       : ['unggah', 'upload'],
                    'report'       : ['laporan', 'report'],
                    'role'         : ['peran', 'role', 'hak akses'],
                    'permission'   : ['izin', 'permission', 'akses'],
                    'admin'        : ['admin', 'administrator'],
                    'dashboard'    : ['dashboard', 'beranda'],
                    'api'          : ['api', 'endpoint', 'request'],
                    'token'        : ['token', 'jwt', 'bearer'],
                };

                // Apply stem expansions for each part
                for (const part of parts) {
                    if (stemMap[part]) stemMap[part].forEach(t => tokens.add(t));
                }
                // Apply for full key
                if (stemMap[key]) stemMap[key].forEach(t => tokens.add(t));

                return Array.from(tokens).filter(t => t.length >= 3);
            }

            // ── Build labelStatusMap: token → status ──────────────────────
            // Priority: missing > partial > implemented (higher severity wins on conflict)
            const labelStatusMap = {};

            function addTokens(featureList, status) {
                for (const featKey of featureList) {
                    const tokens = featureToTokens(featKey);
                    for (const tok of tokens) {
                        // Only overwrite if new status has higher severity
                        const existing = labelStatusMap[tok];
                        const severity = { missing: 3, partial: 2, implemented: 1 };
                        if (!existing || severity[status] > severity[existing]) {
                            labelStatusMap[tok] = status;
                        }
                    }
                }
            }

            // Add in priority order (lowest priority first, higher overwrites)
            addTokens(implemented, 'implemented');
            addTokens(partial,     'partial');
            addTokens(missing,     'missing');

            // Legacy node_status support
            for (const [label, isOk] of Object.entries(nodeStatusOld)) {
                const tok = label.toLowerCase();
                labelStatusMap[tok] = isOk ? 'implemented' : 'missing';
            }

            // ── Apply colors to SVG nodes ─────────────────────────────────
            function getNodeText(nodeEl) {
                // Mermaid v11 renders labels in <span> inside <foreignObject>
                const fo = nodeEl.querySelector('foreignObject');
                if (fo) return fo.textContent.trim().toLowerCase();
                // Fallback: <text> elements
                return Array.from(nodeEl.querySelectorAll('text'))
                    .map(t => t.textContent).join(' ').trim().toLowerCase();
            }

            function matchNodeStatus(nodeText) {
                if (!nodeText) return null;

                // Pass 1: exact substring match with longest token wins
                let bestStatus  = null;
                let bestLen     = 0;
                for (const [tok, status] of Object.entries(labelStatusMap)) {
                    if (nodeText.includes(tok) && tok.length > bestLen) {
                        bestStatus = status;
                        bestLen    = tok.length;
                    }
                }
                if (bestStatus) return bestStatus;

                // Pass 2: reverse check — does the token contain words from node text?
                const nodeWords = nodeText.split(/[\s\/,&]+/).filter(w => w.length > 2);
                for (const word of nodeWords) {
                    for (const [tok, status] of Object.entries(labelStatusMap)) {
                        if (tok.includes(word) && word.length >= 4) {
                            return status;
                        }
                    }
                }

                return null;
            }

            function applyColors() {
                const container = document.getElementById('compliance-mermaid');
                if (!container) return;
                const svg = container.querySelector('svg');
                if (!svg) { setTimeout(applyColors, 300); return; }
                const allNodes = svg.querySelectorAll('.node');
                if (allNodes.length === 0) { setTimeout(applyColors, 300); return; }

                allNodes.forEach(nodeEl => {
                    const nodeText     = getNodeText(nodeEl);
                    const matchedStatus= matchNodeStatus(nodeText);
                    if (!matchedStatus || !colors[matchedStatus]) return;

                    const c = colors[matchedStatus];

                    // Color shape backgrounds
                    nodeEl.querySelectorAll('rect, circle, polygon, ellipse').forEach(shape => {
                        shape.style.fill        = c.fill;
                        shape.style.stroke      = c.stroke;
                        shape.style.strokeWidth = c.strokeWidth;
                        shape.style.transition  = 'all 0.3s ease';
                    });

                    // Color path elements that are NOT arrows
                    nodeEl.querySelectorAll('path').forEach(p => {
                        if (p.getAttribute('marker-end')) return; // skip arrows
                        p.style.fill        = c.fill;
                        p.style.stroke      = c.stroke;
                        p.style.strokeWidth = c.strokeWidth;
                        p.style.transition  = 'all 0.3s ease';
                    });

                    // Color text
                    nodeEl.querySelectorAll('span, text, p, div, label').forEach(t => {
                        t.style.color = c.text;
                        t.style.fill  = c.text;
                    });
                });
            }

            // Retry strategy: Mermaid renders asynchronously
            [600, 1200, 2500, 4000].forEach(delay => setTimeout(applyColors, delay));

            // Also re-color after Mermaid fires its own render event
            if (typeof mermaid !== 'undefined') {
                mermaid.parseError = function() {};
                // Watch for SVG insertion via MutationObserver
                const mo = new MutationObserver(() => { applyColors(); });
                const cmEl = document.getElementById('compliance-mermaid');
                if (cmEl) mo.observe(cmEl, { childList: true, subtree: true });
            }
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
            container = document.getElementById('cm-container');
            container.addEventListener('wheel', function(e) {
                e.preventDefault();
                if (e.deltaY < 0) cmZoomIn();
                else cmZoomOut();
            }, { passive: false });
        }
    </script>

    {{-- Copy AI Prompt Script --}}
    <script>
        function copyAiPrompt() {
            const textarea = document.getElementById('ai-fix-prompt');
            const btn      = document.getElementById('copy-prompt-btn');
            const copyIcon = document.getElementById('copy-icon');
            const checkIcon= document.getElementById('check-icon');
            const btnText  = document.getElementById('copy-btn-text');

            if (!textarea) return;

            navigator.clipboard.writeText(textarea.value).then(() => {
                // Show success state
                copyIcon.classList.add('hidden');
                checkIcon.classList.remove('hidden');
                btnText.textContent = 'Copied!';
                btn.classList.remove('bg-violet-600/80', 'hover:bg-violet-600', 'border-violet-500/50');
                btn.classList.add('bg-emerald-700/60', 'border-emerald-500/50');

                setTimeout(() => {
                    copyIcon.classList.remove('hidden');
                    checkIcon.classList.add('hidden');
                    btnText.textContent = 'Copy Prompt';
                    btn.classList.add('bg-violet-600/80', 'hover:bg-violet-600', 'border-violet-500/50');
                    btn.classList.remove('bg-emerald-700/60', 'border-emerald-500/50');
                }, 2500);
            }).catch(() => {
                // Fallback for older browsers
                textarea.select();
                document.execCommand('copy');
                btnText.textContent = 'Copied!';
                setTimeout(() => { btnText.textContent = 'Copy Prompt'; }, 2000);
            });
        }
    </script>

</x-app-layout>

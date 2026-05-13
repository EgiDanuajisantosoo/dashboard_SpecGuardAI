<x-app-layout>
    <x-slot name="title">PRD Input - SpecGuard AI</x-slot>

    <!-- Top Navbar -->
    <header class="h-16 border-b border-[#2A2A2A] flex items-center justify-between px-8 flex-shrink-0">
        <div class="flex items-center gap-6 h-full">
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm font-medium">SpecGuard</span>
                <span class="text-gray-600">/</span>
                <span class="text-white text-sm font-semibold">core-api</span>
            </div>
            <div class="h-4 w-px bg-[#2A2A2A]"></div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                <span class="text-xs text-gray-500 font-medium tracking-wide uppercase">Status: Draft</span>
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
    <div class="flex-1 overflow-auto p-8">
        <div class="max-w-6xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">Document Input</h2>
                <p class="text-gray-400 text-sm">Provide product requirements to initiate automated specification drafting.</p>
                
                @if(session('error'))
                    <div class="mt-4 p-4 bg-red-500/10 border border-red-500/50 rounded-lg text-red-400 text-sm">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mt-4 p-4 bg-red-500/10 border border-red-500/50 rounded-lg text-red-400 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Content Grid -->
            <form method="POST" action="{{ route('project.generate') }}">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column: Editor -->
                <div class="col-span-2 flex flex-col h-[600px] border border-[#2A2A2A] rounded-lg bg-[#161616] overflow-hidden">
                    
                    <!-- Editor Tabs -->
                    <div class="flex items-center justify-between px-4 border-b border-[#2A2A2A] h-11 bg-[#121212]">
                        <div class="flex items-center gap-6 h-full">
                            <button id="tab-raw-text" onclick="switchTab('raw-text')" class="text-gray-300 text-sm font-medium border-b-2 border-gray-400 h-full pt-[2px] transition-colors">Raw Text</button>
                            <button id="tab-file-upload" onclick="switchTab('file-upload')" class="text-gray-500 hover:text-gray-300 text-sm font-medium border-b-2 border-transparent h-full pt-[2px] transition-colors">File Upload</button>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            <span class="text-[10px] font-bold tracking-wider">MD SUPPORTED</span>
                        </div>
                    </div>

                    <!-- Content Panels -->
                    <!-- Editor Textarea (Raw Text) -->
                    <div id="content-raw-text" class="flex-1 p-4 bg-[#161616] flex flex-col">
                        <textarea name="raw_text" class="w-full flex-1 bg-transparent resize-none outline-none border-none text-gray-400 text-sm font-['JetBrains_Mono',_monospace] leading-relaxed" spellcheck="false" placeholder="# Overview&#10;Describe the core objective of this feature...&#10;&#10;## Goals & Non-Goals&#10;- Goal 1...&#10;&#10;## User Stories&#10;- As a [role], I want to [action] so that [benefit]..."></textarea>
                    </div>
                    
                    <!-- File Upload UI -->
                    <div id="content-file-upload" class="hidden flex-1 p-8 bg-[#161616] flex items-center justify-center">
                        <label for="file-upload-input" class="w-full h-full border-2 border-dashed border-[#333] hover:border-gray-500 hover:bg-[#1C1C1C] transition-all rounded-xl flex flex-col items-center justify-center bg-[#1A1A1A] cursor-pointer group">
                            <div class="w-12 h-12 bg-[#252525] group-hover:bg-[#333] transition-colors rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <p class="text-sm text-gray-300 font-medium mb-1">Click to upload or drag and drop</p>
                            <p class="text-xs text-gray-500">PDF documents only (MAX. 10MB)</p>
                            <input type="file" accept=".pdf" class="hidden" id="file-upload-input">
                        </label>
                    </div>

                    <!-- Editor Footer / Actions -->
                    <div class="p-3 border-t border-[#2A2A2A] bg-[#121212] flex items-center justify-between">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] text-gray-500 font-bold tracking-wider uppercase">Template</span>
                            <div class="relative">
                                <select class="appearance-none w-32 bg-[#252525] border border-[#333] text-gray-300 text-sm rounded px-3 py-1.5 focus:outline-none focus:border-gray-500 cursor-pointer">
                                    <option>Standard</option>
                                    <option>RFC</option>
                                    <option>API Spec</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-5 py-2 rounded-md shadow-sm transition-all focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-[#121212] inline-block">
                            Generate Flow & Spec
                        </button>
                    </div>
                </div>

                <!-- Right Column: Meta & History -->
                <div class="col-span-1 flex flex-col gap-6">
                    
                    <!-- Meta Info -->
                    <div class="bg-[#161616] border border-[#2A2A2A] rounded-lg p-5">
                        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Document Properties
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1.5 block">Project Area</label>
                                <input type="text" name="project_area" value="Authentication Service" class="w-full bg-[#121212] border border-[#333] text-gray-300 text-sm rounded-md px-3 py-2 focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                             <div>
                                <label class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1.5 block">Target Repository URL</label>
                                <input type="text" name="target_repo" placeholder="https://github.com/username/repo.git" class="w-full bg-[#121212] border border-[#333] text-gray-300 text-sm rounded-md px-3 py-2 focus:outline-none focus:border-indigo-500 transition-colors font-mono">
                                <p class="text-[10px] text-gray-600 mt-1">GitHub clone URL (used for webhook matching)</p>
                            </div>
                        </div>
                    </div>

                    <!-- History / Recent -->
                    <div class="bg-[#161616] border border-[#2A2A2A] rounded-lg p-5 flex-1">
                        <h3 class="text-sm font-semibold text-white mb-4">Recent Documents</h3>
                        <div class="space-y-3">
                            @forelse($projects as $proj)
                                <a href="{{ route('project.show', $proj->id) }}" class="block p-3 rounded border border-[#333] hover:border-gray-500 hover:bg-[#1C1C1C] transition-all group">
                                    <div class="flex items-start justify-between mb-1">
                                        <h4 class="text-sm text-gray-300 font-medium group-hover:text-white">{{ $proj->name }}</h4>
                                        <span class="text-[10px] text-gray-500">{{ $proj->created_at->diffForHumans(null, true, true) }} ago</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-2">
                                        @php
                                            $latestProjAudit = $proj->audits->sortByDesc('created_at')->first();
                                            $isComplete = $latestProjAudit && $latestProjAudit->status === 'complete';
                                            $isPartial = $latestProjAudit && $latestProjAudit->status === 'partial';
                                        @endphp
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isComplete ? 'bg-emerald-500' : ($isPartial ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                        <span class="text-xs text-gray-500">{{ $latestProjAudit ? 'Score: ' . $latestProjAudit->score . '%' : 'No Audit Yet' }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="text-sm text-gray-500 text-center py-4">No projects found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            </form>

            {{-- Flow Preview Panel — shows latest project's spec + live preview while typing --}}
           {{--  <div class="mt-6 bg-[#161616] border border-[#2A2A2A] rounded-xl overflow-hidden shadow-sm">
                <div class="h-12 border-b border-[#2A2A2A] px-5 flex items-center justify-between bg-[#121212]">
                    <span class="text-sm font-medium text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                        Flow Preview
                        <span id="preview-badge" class="text-[10px] font-mono px-2 py-0.5 rounded bg-indigo-900/30 text-indigo-400 border border-indigo-800/40 hidden">Live</span>
                    </span>
                    <div class="flex items-center gap-2">
                        @if(count($projects) > 0)
                        <span class="text-xs text-gray-500">Latest: <span class="text-gray-300">{{ $projects->first()->name }}</span></span>
                        @endif
                        <span id="preview-status" class="text-[10px] text-gray-600"></span>
                    </div>
                </div>--}}

                {{-- Diagram Container --}}
               {{--  <div id="flow-preview-container" class="p-6 bg-[#0A0A0A] overflow-auto flex items-center justify-center" style="min-height: 300px;">
                    @if($projects->isNotEmpty() && $projects->first()->spec_content)
                        <div class="mermaid" style="font-size: 15px;">
                            {!! $projects->first()->spec_content !!}
                        </div>
                    @else
                        <div id="preview-placeholder" class="flex flex-col items-center gap-3 text-center">
                            <svg class="w-10 h-10 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                            <p class="text-sm text-gray-600">Flow preview will appear here after generating a spec.</p>
                        </div>
                    @endif
                    <div id="live-preview-container" class="hidden w-full flex items-center justify-center">
                        <div id="live-mermaid" class="mermaid" style="font-size: 14px;"></div>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>

    <!-- Tab Switching + Live Mermaid Preview Script -->
    <script>
        function switchTab(tabId) {
            document.getElementById('content-raw-text').classList.add('hidden');
            document.getElementById('content-raw-text').classList.remove('flex');
            document.getElementById('content-file-upload').classList.add('hidden');
            document.getElementById('content-file-upload').classList.remove('flex');

            document.getElementById('tab-raw-text').className = "text-gray-500 hover:text-gray-300 text-sm font-medium border-b-2 border-transparent h-full pt-[2px] transition-colors";
            document.getElementById('tab-file-upload').className = "text-gray-500 hover:text-gray-300 text-sm font-medium border-b-2 border-transparent h-full pt-[2px] transition-colors";

            if (tabId === 'raw-text') {
                document.getElementById('content-raw-text').classList.remove('hidden');
                document.getElementById('content-raw-text').classList.add('flex');
                document.getElementById('tab-raw-text').className = "text-gray-300 text-sm font-medium border-b-2 border-gray-400 h-full pt-[2px] transition-colors";
            } else if (tabId === 'file-upload') {
                document.getElementById('content-file-upload').classList.remove('hidden');
                document.getElementById('content-file-upload').classList.add('flex');
                document.getElementById('tab-file-upload').className = "text-gray-300 text-sm font-medium border-b-2 border-gray-400 h-full pt-[2px] transition-colors";
            }
        }

        // ── Live Mermaid Preview ────────────────────────────────────────────
        // Detects graph/flowchart code in the PRD textarea and renders it
        const textarea = document.querySelector('textarea[name="raw_text"]');
        const liveContainer = document.getElementById('live-preview-container');
        const liveMermaid   = document.getElementById('live-mermaid');
        const statusEl      = document.getElementById('preview-status');
        const badge         = document.getElementById('preview-badge');
        let debounceTimer;

        function extractMermaid(text) {
            // Match ```mermaid ... ``` blocks
            const fenced = text.match(/```mermaid\s*([\s\S]+?)```/i);
            if (fenced) return fenced[1].trim();
            // Match bare graph / flowchart blocks
            const bare = text.match(/((?:graph|flowchart|sequenceDiagram|classDiagram|stateDiagram|gantt|pie|erDiagram|journey)[\s\S]+)/i);
            if (bare) return bare[1].trim();
            return null;
        }

        async function renderLivePreview(mermaidCode) {
            if (!mermaidCode || !liveMermaid) return;
            try {
                statusEl.textContent = 'Rendering…';
                const id = 'live-graph-' + Date.now();
                const { svg } = await mermaid.render(id, mermaidCode);
                liveMermaid.innerHTML = svg;
                liveContainer.classList.remove('hidden');
                badge.classList.remove('hidden');
                statusEl.textContent = '';
            } catch (e) {
                statusEl.textContent = 'Syntax error in diagram';
                liveContainer.classList.add('hidden');
            }
        }

        if (textarea) {
            textarea.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const code = extractMermaid(textarea.value);
                    if (code) {
                        renderLivePreview(code);
                    } else {
                        liveContainer.classList.add('hidden');
                        badge.classList.add('hidden');
                        statusEl.textContent = '';
                    }
                }, 1500);
            });
        }
    </script>

</x-app-layout>


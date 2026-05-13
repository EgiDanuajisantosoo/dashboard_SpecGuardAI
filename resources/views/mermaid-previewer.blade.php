<x-app-layout>
    <x-slot name="title">Mermaid Previewer - SpecGuard AI</x-slot>

    <!-- Top Navbar -->
    <header class="h-16 border-b border-[#2A2A2A] flex items-center justify-between px-8 flex-shrink-0 bg-[#121212]/80 backdrop-blur-md sticky top-0 z-10">
        <div class="flex items-center gap-6 h-full">
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm font-medium">SpecGuard</span>
                <span class="text-gray-600">/</span>
                <span class="text-white text-sm font-semibold">{{ $project ? $project->name : 'Project' }}</span>
            </div>
            <div class="h-4 w-px bg-[#2A2A2A]"></div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                <span class="text-xs text-yellow-500 font-bold tracking-wide uppercase">Pending Audit</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-300 border border-[#333] rounded-md hover:bg-[#1A1A1A] transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                GitHub
            </button>
            <button class="text-gray-400 hover:text-white transition-colors relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border border-[#121212]"></span>
            </button>
            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 border border-[#333] ml-2"></div>
        </div>
    </header>

    <!-- Workspace Area -->
    <div class="flex-1 overflow-auto p-8 z-0 relative">
        <div class="max-w-7xl mx-auto flex flex-col gap-6">

            <!-- Sub Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-2 flex items-center gap-3">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        {{ $project ? $project->name . '.mermaid' : 'flowchart.mermaid' }}
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase border border-[#333] bg-[#252525] text-gray-400">v1.2.4</span>
                    </h2>
                    <p class="text-gray-400 text-sm">Review the generated Mermaid flow diagram for your project.</p>
                </div>
                <div class="flex gap-3">
                    @if($project)
                    <form method="POST" action="{{ route('project.regenerate', $project->id) }}" id="regenerate-form">
                        @csrf
                        <button type="submit" id="regenerate-btn"
                            class="px-4 py-2 text-sm font-medium text-gray-300 border border-[#333] rounded-md hover:bg-[#1A1A1A] transition-colors flex items-center gap-2"
                            onclick="this.disabled=true; document.getElementById('regen-spinner').classList.remove('hidden'); this.querySelector('#regen-text').textContent='Regenerating...'">
                            <svg id="regen-spinner" class="w-4 h-4 hidden animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <svg id="regen-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span id="regen-text">Regenerate</span>
                        </button>
                    </form>
                    @endif
                    <a href="/compliance" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-500 transition-colors flex items-center gap-2 shadow-[0_0_15px_rgba(79,70,229,0.3)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Enable Audit
                    </a>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-lg bg-emerald-900/30 border border-emerald-800/50 text-emerald-400 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-lg bg-red-900/30 border border-red-800/50 text-red-400 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                {{ session('error') }}
            </div>
            @endif

            <!-- Main Editor Split -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- mermaid Editor Side -->
                <div class="col-span-2 bg-[#161616] border border-[#2A2A2A] rounded-xl flex flex-col overflow-hidden shadow-sm">
                    <div class="h-12 border-b border-[#2A2A2A] bg-[#121212] px-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-500/20 border border-red-500/50"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500/20 border border-yellow-500/50"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/20 border border-green-500/50"></div>
                            </div>
                            <span class="text-xs font-mono text-gray-400">{{ $project ? $project->name . '.mermaid' : 'flowchart.mermaid' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-mono text-gray-500">
                            <span>mermaid</span>
                            <span>UTF-8</span>
                        </div>
                    </div>
                    
                    <div class="flex-1 p-4 relative font-['JetBrains_Mono',_monospace] text-sm overflow-auto h-[600px] leading-relaxed">
                        <!-- Line Numbers & Code -->
                        <div class="flex">
                            <div class="text-gray-300 whitespace-pre flex flex-col font-mono text-sm">
                                {{ $project ? $project->spec_content : 'No content generated.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requirement Checklist — Dynamic from DB -->
                <div class="col-span-1 flex flex-col gap-6">
                    <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl overflow-hidden shadow-sm flex flex-col">
                        <div class="h-12 border-b border-[#2A2A2A] px-5 flex items-center justify-between bg-[#121212]">
                            <div class="flex items-center gap-2 text-white font-medium">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                Requirement Checklist
                            </div>
                            @if($project && !empty($project->prd_requirements))
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-indigo-900/30 text-indigo-400 border border-indigo-800/40">
                                {{ count($project->prd_requirements) }} items
                            </span>
                            @endif
                        </div>

                        <div class="p-4 flex flex-col gap-2 overflow-auto flex-1">
                        @if($project && !empty($project->prd_requirements))
                            @foreach($project->prd_requirements as $req)
                            @php
                                $type = $req['type'] ?? 'functional';
                                $typeColors = match($type) {
                                    'security'       => 'bg-red-900/20 text-red-400 border-red-800/30',
                                    'non-functional' => 'bg-yellow-900/20 text-yellow-400 border-yellow-800/30',
                                    'ui'             => 'bg-purple-900/20 text-purple-400 border-purple-800/30',
                                    default          => 'bg-indigo-900/20 text-indigo-400 border-indigo-800/30',
                                };
                            @endphp
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-[#111] border border-[#222] hover:border-[#333] transition-colors">
                                <div class="mt-0.5 flex-shrink-0">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 mb-0.5">
                                        <h4 class="text-sm text-gray-200 font-medium truncate">{{ $req['title'] ?? 'Requirement' }}</h4>
                                        <span class="text-[9px] font-mono px-1.5 py-0.5 rounded border {{ $typeColors }} flex-shrink-0">{{ ucfirst($type) }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 leading-relaxed">{{ $req['description'] ?? '' }}</p>
                                </div>
                            </div>
                            @endforeach
                        @elseif($project)
                            {{-- No requirements yet — show prompt to regenerate --}}
                            <div class="flex flex-col items-center justify-center py-8 text-center gap-3">
                                <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <div>
                                    <p class="text-sm text-gray-500">No requirements yet.</p>
                                    <p class="text-xs text-gray-600 mt-1">Click <strong class="text-gray-400">Regenerate</strong> to extract from PRD.</p>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <p class="text-sm text-gray-500">Generate a project first.</p>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flow Preview — Full Width Large Panel -->
            <div class="bg-[#161616] border border-[#2A2A2A] rounded-xl flex flex-col overflow-hidden shadow-sm">
                <!-- Header -->
                <div class="h-12 border-b border-[#2A2A2A] px-4 flex items-center justify-between bg-[#121212]">
                    <span class="text-sm font-medium text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                        Flow Preview
                    </span>
                    <div class="flex items-center gap-2">
                        <button onclick="zoomIn()" class="px-2 py-1 text-xs text-gray-400 border border-[#333] rounded hover:bg-[#252525] transition-colors">＋ Zoom In</button>
                        <button onclick="zoomOut()" class="px-2 py-1 text-xs text-gray-400 border border-[#333] rounded hover:bg-[#252525] transition-colors">－ Zoom Out</button>
                        <button onclick="resetZoom()" class="px-2 py-1 text-xs text-gray-400 border border-[#333] rounded hover:bg-[#252525] transition-colors">⟳ Reset</button>
                    </div>
                </div>
                <!-- Diagram Area -->
                <div id="flow-container" class="p-6 bg-[#0A0A0A] overflow-auto flex items-center justify-center" style="min-height: 520px;">
                    @if($project && $project->spec_content)
                        <div id="mermaid-wrapper" style="transform-origin: center; transition: transform 0.2s ease;">
                            <div class="mermaid" style="font-size: 15px;">
                                {!! $project->spec_content !!}
                            </div>
                        </div>
                    @else
                        <p class="text-[10px] text-gray-600 text-center italic">No flowchart available. Generate a spec first.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Zoom Controls Script -->
    <script>
        let scale = 1;
        const wrapper = document.getElementById('mermaid-wrapper');

        function zoomIn() {
            scale = Math.min(scale + 0.2, 3);
            applyZoom();
        }
        function zoomOut() {
            scale = Math.max(scale - 0.2, 0.3);
            applyZoom();
        }
        function resetZoom() {
            scale = 1;
            applyZoom();
        }
        function applyZoom() {
            if (wrapper) wrapper.style.transform = `scale(${scale})`;
        }

        // Mouse wheel zoom on the container
        const container = document.getElementById('flow-container');
        if (container) {
            container.addEventListener('wheel', function(e) {
                e.preventDefault();
                if (e.deltaY < 0) zoomIn();
                else zoomOut();
            }, { passive: false });
        }
    </script>

</x-app-layout>


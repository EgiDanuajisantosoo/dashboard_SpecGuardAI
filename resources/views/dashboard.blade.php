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
            </div>

            <!-- Content Grid -->
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
                        <textarea class="w-full flex-1 bg-transparent resize-none outline-none border-none text-gray-400 text-sm font-['JetBrains_Mono',_monospace] leading-relaxed" spellcheck="false" placeholder="# Overview&#10;Describe the core objective of this feature...&#10;&#10;## Goals & Non-Goals&#10;- Goal 1...&#10;&#10;## User Stories&#10;- As a [role], I want to [action] so that [benefit]..."></textarea>
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
                        <a href="/openspec" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-5 py-2 rounded-md shadow-sm transition-all focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-[#121212] inline-block">
                            Generate OpenSpec
                        </a>
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
                                <input type="text" value="Authentication Service" class="w-full bg-[#121212] border border-[#333] text-gray-300 text-sm rounded-md px-3 py-2 focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1.5 block">Target Repository</label>
                                <select class="w-full bg-[#121212] border border-[#333] text-gray-300 text-sm rounded-md px-3 py-2 focus:outline-none focus:border-indigo-500 transition-colors appearance-none">
                                    <option>specguard-demo/core-api</option>
                                    <option>specguard-demo/frontend</option>
                                </select>
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
        </div>
    </div>

    <!-- Simple JS for Tab Switching -->
    <script>
        function switchTab(tabId) {
            // Hide all contents
            document.getElementById('content-raw-text').classList.add('hidden');
            document.getElementById('content-raw-text').classList.remove('flex');
            document.getElementById('content-file-upload').classList.add('hidden');
            document.getElementById('content-file-upload').classList.remove('flex');
            
            // Reset all tabs
            document.getElementById('tab-raw-text').className = "text-gray-500 hover:text-gray-300 text-sm font-medium border-b-2 border-transparent h-full pt-[2px] transition-colors";
            document.getElementById('tab-file-upload').className = "text-gray-500 hover:text-gray-300 text-sm font-medium border-b-2 border-transparent h-full pt-[2px] transition-colors";
            
            // Show selected content
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
    </script>
</x-app-layout>

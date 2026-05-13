<aside class="w-[260px] flex-shrink-0 border-r border-[#2A2A2A] bg-[#121212] flex flex-col justify-between z-20">
    <div>
        <!-- Logo area -->
        <div class="h-16 flex items-center px-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-[#1A1A1A] border border-[#333] flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 3V10m0 0l-4-4m4 4l4-4m-4 17a9 9 0 110-18 9 9 0 010 18z"></path></svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white tracking-tight leading-tight">SpecGuard AI</h1>
                    <p class="text-[10px] text-gray-500 tracking-wider uppercase font-medium">Project Auditor</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-6 px-3 space-y-1">
            <a href="/" class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors text-sm font-medium {{ request()->is('/') ? 'bg-[#252525] text-white border border-[#333]/50' : 'text-gray-400 hover:text-white hover:bg-[#1A1A1A]' }}">
                <svg class="w-4 h-4 {{ request()->is('/') ? 'text-indigo-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                1. PRD & Specification
            </a>
            <a href="/mermaid-previewer" class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors text-sm font-medium {{ request()->is('mermaid-previewer') ? 'bg-[#252525] text-white border border-[#333]/50' : 'text-gray-400 hover:text-white hover:bg-[#1A1A1A]' }}">
                <svg class="w-4 h-4 {{ request()->is('mermaid-previewer') ? 'text-indigo-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                2. Mermaid Previewer
            </a>
            <a href="/compliance" class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors text-sm font-medium {{ request()->is('compliance') ? 'bg-[#252525] text-white border border-[#333]/50' : 'text-gray-400 hover:text-white hover:bg-[#1A1A1A]' }}">
                <svg class="w-4 h-4 {{ request()->is('compliance') ? 'text-emerald-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                3. Live Audit Board
            </a>
        </nav>
    </div>

    <!-- Bottom Links -->
    <!-- <div class="p-3 border-t border-[#2A2A2A]">
        <nav class="space-y-1 mb-4">
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-gray-400 hover:text-white hover:bg-[#1A1A1A] rounded-md transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-gray-400 hover:text-white hover:bg-[#1A1A1A] rounded-md transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Documentation
            </a>
        </nav>
        <button class="w-full flex items-center justify-center gap-2 py-2 border border-[#333] hover:border-gray-500 rounded-md text-sm font-medium text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Project
        </button>
    </div> -->
</aside>

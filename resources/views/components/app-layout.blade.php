<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SpecGuard AI' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
    <style>
        /* Make Mermaid flowchart nodes larger */
        .mermaid svg { font-size: 15px !important; }
        .mermaid .node rect,
        .mermaid .node circle,
        .mermaid .node ellipse,
        .mermaid .node polygon { min-width: 120px; }
        .mermaid .node foreignObject { min-width: 120px; min-height: 40px; }
        .mermaid .node .label { font-size: 14px !important; padding: 8px 12px !important; }
        .mermaid .nodeLabel { font-size: 14px !important; }
        .mermaid .edgeLabel { font-size: 12px !important; }
    </style>
    <script>
        mermaid.initialize({
            startOnLoad: true,
            theme: 'dark',
            securityLevel: 'loose',
            suppressErrorNotifications: false,
            flowchart: {
                nodeSpacing: 60,
                rankSpacing: 80,
                padding: 20,
                htmlLabels: true,
                useMaxWidth: false,
            },
            fontSize: 15,
        });
    </script>
</head>
<body class="antialiased flex h-screen overflow-hidden selection:bg-indigo-500/30 bg-[#121212] text-[#E0E0E0] font-['Inter',_sans-serif] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar]:h-2 [&::-webkit-scrollbar-track]:bg-[#121212] [&::-webkit-scrollbar-thumb]:bg-[#333] [&::-webkit-scrollbar-thumb]:rounded [&::-webkit-scrollbar-thumb:hover]:bg-[#555]">
    
    <!-- Sidebar / Global Navbar -->
    <x-navbar />

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0 {{ $mainBg ?? 'bg-[#121212]' }} relative">
        {{ $slot }}
    </main>

</body>
</html>

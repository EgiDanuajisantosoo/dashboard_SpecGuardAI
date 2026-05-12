## 1. Structure Integration

- [x] 1.1 Replace the raw HTML document structure (html, head, body) in `resources/views/dashboard.blade.php` with `<x-ui::app-layout>`.
- [x] 1.2 Include the page title within the `<x-slot name="title">`.
- [x] 1.3 Move the Mermaid initialization script inside `<x-ui::app-layout>` or just before the closing tag, ensuring it loads properly.

## 2. Layout and Theming Refactoring

- [x] 2.1 Refactor the main content container (`<main class="max-w-7xl mx-auto px-4 py-12">`) to match the dark theme layout (e.g., `flex-1 overflow-auto p-8 relative z-0`).
- [x] 2.2 Update the empty state UI (`No projects found`) to use dark-themed colors (`bg-[#161616]`, `border-[#2A2A2A]`, etc.).
- [x] 2.3 Refactor the `$projects` iteration loop so that each project card uses dark-theme classes (`bg-[#161616]`, `border-[#2A2A2A]`, `text-white`, `text-gray-400`).

## 3. Component Details & Mermaid Styles

- [x] 3.1 Update the compliance score progress bar to use dark-compatible colors (`bg-[#2A2A2A]` for the track, maintaining `green-500`, `yellow-500`, `red-500` for progress).
- [x] 3.2 Update the `missing_requirements` alert box to use dark-theme error colors (`bg-red-900/20`, `border-red-900/50`, `text-red-400`).
- [x] 3.3 Ensure the `mermaid` class container integrates seamlessly, adjusting text/fill colors if necessary using the `<style>` block (updating node colors for dark mode visibility).
- [x] 3.4 Verify the `updateMermaidColors()` javascript function correctly toggles the appropriate nodes within the new layout.

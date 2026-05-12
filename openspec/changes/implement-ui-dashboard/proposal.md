## Why

The main `dashboard.blade.php` currently uses a standalone HTML structure and a white theme that deviates from the finalized SpecGuard AI design language established in the `resources/views/ui/` directory. We need to merge the dynamic logic (fetching projects, displaying health scores, and rendering Mermaid graphs) with the official `<x-ui::app-layout>` dark-themed UI to provide a consistent and polished user experience.

## What Changes

- **Layout Integration**: Wrap the content of `resources/views/dashboard.blade.php` inside the `<x-ui::app-layout>` component, removing the raw HTML boilerplate.
- **Theme Alignment**: Restyle the dynamic project cards, health scores, and compliance tags to match the established dark theme (`bg-[#161616]`, `border-[#2A2A2A]`, etc.).
- **Dynamic Data Preservation**: Retain the `@foreach($projects as $project)` loop, the Mermaid diagram logic, and the missing requirements logic, but ensure they fit aesthetically within the dark UI.

## Capabilities

### New Capabilities
- `dashboard-ui-integration`: Merging the backend dynamic project listing and compliance monitoring logic with the new dark-themed frontend UI layout.

### Modified Capabilities
- (None)

## Impact

- `resources/views/dashboard.blade.php` will be completely refactored.
- The user experience will be unified across all pages.

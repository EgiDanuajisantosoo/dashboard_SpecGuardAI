## Context

The user has provided a custom `resources/views/dashboard.blade.php` file that fetches real project data from a backend and displays it using a white theme and standalone HTML structure. Concurrently, a beautiful dark-themed layout and UI component structure has been built in `resources/views/ui/` consisting of an `<x-ui::app-layout>` wrapper and an `<x-ui::navbar>`.

## Goals / Non-Goals

**Goals:**
- Migrate the dynamic data processing logic (`$projects`, `$project->audits`, missing requirements loop) from the standalone `dashboard.blade.php` into the dark-themed UI layout.
- Adapt the styling of the data elements (health score cards, audit status) to use the established dark theme (`bg-[#161616]`, `border-[#2A2A2A]`, etc.).
- Maintain the Mermaid graph visualization functionality within the new UI.

**Non-Goals:**
- Do not alter the backend controller logic (e.g., `DashboardController`).
- Do not change the overall layout structure defined in `app-layout.blade.php`.

## Decisions

- **UI Component Reuse**: Use the exact `<x-ui::app-layout>` wrapper to ensure the sidebar and top navigation remain identical to the static mockups.
- **Card Styling Adaptation**: The white-themed project cards will be restyled to match the dark theme. E.g., `bg-white` becomes `bg-[#161616]`, borders become `border-[#2A2A2A]`.
- **Mermaid Graph Styling**: The Mermaid script will be preserved, but its container will be styled to fit the dark theme context.

## Risks / Trade-offs

- **Risk**: Tailwind classes for the dark theme might conflict with any hardcoded inline styles in the user's provided code.
- **Mitigation**: Carefully replace only the utility classes and avoid changing structural divs unless necessary.

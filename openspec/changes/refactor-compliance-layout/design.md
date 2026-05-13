## Context

The `compliance.blade.php` view currently uses a full-width vertical stack for its sections. This makes the page very long and buries critical information like AI fix recommendations and feature status below the large Mermaid diagram.

## Goals / Non-Goals

**Goals:**
- Implement a side-by-side layout for the compliance dashboard.
- Prioritize the "AI Fix Prompt" by moving it to the top of the secondary information column.
- Improve the overall scannability of the dashboard.

**Non-Goals:**
- Adding new functional data to the view.
- Changing the Mermaid diagram generation logic.

## Decisions

- **Two-Column Grid**: We will use a Tailwind CSS grid (`grid-cols-1 lg:grid-cols-12`).
    - **Workflow Compliance Map**: Will occupy the left column (`lg:col-span-7` or `lg:col-span-8`).
    - **Information Stack**: Will occupy the right column (`lg:col-span-5` or `lg:col-span-4`).
- **Information Stack Order**: The components in the right column will be ordered as:
    1. AI Fix Prompt (Rekomendasi Prompt AI).
    2. Recent Commits.
    3. Feature Status.
- **Sticky Column**: Consider making the right column sticky if the Mermaid map is significantly longer, ensuring recommendations are always visible.
- **Height Management**: Set a consistent `min-height` for the Mermaid container to prevent layout shifts.

## Risks / Trade-offs

- **Diagram Visibility**: Narrower columns might cut off parts of wide Mermaid diagrams.
    - **Mitigation**: Ensure the `cm-container` (Mermaid container) has `overflow-auto` and keep the zoom/pan tools fully functional.
- **Mobile Experience**: A two-column layout isn't feasible on mobile.
    - **Mitigation**: Use Tailwind's responsive prefixes (e.g., `grid-cols-1 lg:grid-cols-12`) to automatically stack sections vertically on smaller screens.

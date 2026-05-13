## Context

The application features a view for reviewing generated Mermaid diagrams. This view and its navigation link are currently labeled "OpenSpec Editor", which is being updated to "Mermaid Previewer" to more accurately describe the functionality.

## Goals / Non-Goals

**Goals:**
- Update the sidebar navigation label.
- Update the page title in the `openspec` view.
- Update the descriptive text in the `openspec` view.

**Non-Goals:**
- No changes to functional logic, routes, or controllers.
- No changes to existing CSS or layout structure.

## Decisions

- **Direct String Replacement**: The labels will be updated directly in the Blade templates. This is the simplest and most effective way for static UI labels.
- **Consistency**: All references to "OpenSpec" as an editor/review page will be transitioned to "Mermaid Previewer" in the target files.

## Risks / Trade-offs

- **Risk**: Hardcoding labels might make localization harder in the future.
- **Mitigation**: The project currently uses hardcoded English strings in Blade files, so this change follows the existing pattern.

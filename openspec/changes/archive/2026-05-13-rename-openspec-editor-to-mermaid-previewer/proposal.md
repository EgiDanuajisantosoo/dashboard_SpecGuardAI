## Why

The current labeling "OpenSpec Editor" is no longer accurate as the focus of the view has shifted to previewing Mermaid diagrams generated from the PRD. Renaming it to "Mermaid Previewer" provides better clarity to the user about the page's purpose.

## What Changes

- Rename "OpenSpec Editor" to "Mermaid Previewer" in the sidebar navigation.
- Update page title and description in the `openspec` view to reflect the change to "Mermaid Previewer".

## Capabilities

### New Capabilities
- None

### Modified Capabilities
- dashboard-core: Rename "OpenSpec Editor" references to "Mermaid Previewer" in requirements.

## Impact

- `resources/views/components/navbar.blade.php`: UI label change in the sidebar.
- `resources/views/openspec.blade.php`: Page title and description text changes.

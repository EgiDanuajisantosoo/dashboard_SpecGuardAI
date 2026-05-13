## Why

The current layout of the compliance map is too large and takes up excessive vertical space, making it difficult for developers to quickly see the AI recommendations and feature status. Reorganizing the layout will improve visibility and usability.

## What Changes

- Refactor the `compliance.blade.php` layout into a two-column grid.
- **Left Column**: Workflow Compliance Map (Mermaid diagram).
- **Right Column**: A vertical stack consisting of:
    1. **Rekomendasi Prompt AI** (moved to the top for immediate visibility).
    2. **Recent Commits**.
    3. **Feature Status**.
- Adjust the dimensions and scaling of the Mermaid diagram container to fit the new side-by-side layout.

## Capabilities

### New Capabilities
- None

### Modified Capabilities
- `project-details`: Update layout requirements to reflect the two-column structure and component ordering.

## Impact

- `resources/views/compliance.blade.php`: Major UI refactoring.
- `app/Http/Controllers/DashboardController.php`: (Potential) Adjustments to data passed if needed, though unlikely.

## Context

The application has a `resources/views/ui` directory containing the latest frontend design for the dashboard, developed independently of the core application logic. The core application logic currently serves dummy testing dashboard pages that are situated outside of this `ui` folder. To bridge the gap, we need to unify the presentation and functional layers by moving the new UI views to the appropriate view locations and integrating the backend data and logic into them.

## Goals / Non-Goals

**Goals:**
- Move all files from `resources/views/ui` to their proper places within `resources/views` (e.g., replacing dummy views or placing them logically based on functionality).
- Delete the old, dummy dashboard views that are currently outside of `resources/views/ui`.
- Update the relevant controllers to render these newly placed views instead of the old dummy ones.
- Pass required data from the controllers to the new views, replacing static UI placeholders with dynamic data.
- Ensure that assets (CSS, JS, images) linked in the UI views continue to function correctly after the move.

**Non-Goals:**
- Completely rewriting the backend logic unless necessary to support the UI views.
- Changing the frontend framework or adding new frontend libraries not already used in the `ui` views.

## Decisions

- **Folder Structure:** The views from `resources/views/ui` will be placed directly into standard folders like `resources/views/dashboard` or `resources/views/layouts`, replacing the dummy files to maintain standard Laravel (or similar framework) conventions.
- **Controller Adjustments:** Controllers responsible for rendering the dashboard (e.g., `DashboardController` or similar core controllers) will be updated to point to the new view paths and supply real dynamic variables instead of relying on the static HTML of the UI templates.

## Risks / Trade-offs

- **Asset Path Breakage:** Moving views might break relative paths for assets. Mitigation: Update paths to use absolute helpers (e.g., `asset()` in Laravel) where appropriate during integration.
- **Missing Functionalities:** The frontend mockups might include features not yet supported by the backend. Mitigation: Log these as future tasks, hide the UI elements temporarily, or implement basic functional scaffolding.

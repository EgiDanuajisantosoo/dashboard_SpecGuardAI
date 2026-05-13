## Why

The current structure has UI views temporarily placed in `resources/views/ui` for frontend development, while dummy testing views still exist outside this folder. This fragmentation causes confusion and technical debt. We need to integrate the actual frontend UI back into the standard `resources/views` directory, remove the dummy testing views, and wire up the real functionalities from the UI to the core application logic to establish a unified and functional dashboard.

## What Changes

- Move all views currently in `resources/views/ui` back to the root of `resources/views` (or appropriate subdirectories within `views`).
- Delete all dummy testing dashboard views that reside outside of `resources/views/ui`.
- Set the application to use the actual dashboard views (previously in `resources/views/ui`) as the main views.
- Implement the functional logic for the UI components in the real dashboard views.
- Update `dashboard-core` logic, controllers, and routes to point to the newly integrated views and handle the required functionalities.

## Capabilities

### New Capabilities
- `dashboard-views-integration`: Integrating frontend UI designs into the core application views and connecting them to the backend functionalities.

### Modified Capabilities
- `dashboard-core`: Modifying the core dashboard logic and routing to utilize the new integrated views and abandoning the old dummy views.

## Impact

- **Views:** Major restructuring of `resources/views`. Deletion of old files, relocation of files from `resources/views/ui`.
- **Controllers:** Updates to dashboard controllers to return the newly positioned views and pass the correct functional data.
- **Routes:** Potential updates to route definitions if view paths or controller methods change.
- **Frontend Assets:** Ensure any asset paths (JS/CSS/images) in the moved views still resolve correctly.

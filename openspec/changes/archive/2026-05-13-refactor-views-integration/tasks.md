## 1. Cleanup Dummy Views

- [x] 1.1 Delete dummy dashboard views outside of `resources/views/ui`
- [x] 1.2 Confirm routes currently using dummy views

## 2. Migrate UI Views

- [x] 2.1 Move all UI view files from `resources/views/ui` to appropriate locations in `resources/views/dashboard` (or root `views` directory depending on previous structure)
- [x] 2.2 Update blade templates (if using Laravel Blade) layouts and includes to reflect new folder paths
- [x] 2.3 Verify and fix any static asset paths (CSS, JS, images) in the migrated views

## 3. Integration & Controller Updates

- [x] 3.1 Update DashboardController (or relevant controllers) to point to the newly migrated views
- [x] 3.2 Inject real dynamic data (e.g., project data, audit values) from controllers into the views to replace mock UI data
- [x] 3.3 Ensure the Mermaid.js integration successfully receives the `node_status` JSON data to render the chart in the new UI layout

## 4. Verification

- [x] 4.1 Test the `/dashboard` route in the browser (or run relevant tests) to ensure the UI renders correctly without errors
- [x] 4.2 Verify all interactive functionalities from the UI are working with real data
- [x] 4.3 Remove the empty `resources/views/ui` folder if not needed anymore

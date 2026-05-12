## 1. AI Integration Refactor

- [x] 1.1 Update `ProcessAuditJob.php` to use `openai-php/laravel` directly.
- [x] 1.2 Implement the audit prompt that compares PRD (`prd_content`) and Spec (`spec_content`) with the code diff.
- [x] 1.3 Ensure the AI returns a structured JSON with `node_status` and `missing_requirements`.

## 2. Controller & Routing

- [x] 2.1 Update `DashboardController@openspec` or create a new `compliance` method to handle data retrieval for the compliance board.
- [x] 2.2 Fix the route for `project.show` in `routes/web.php` (remove the backslash typo).

## 3. Frontend Enhancement (Compliance View)

- [x] 3.1 Update `compliance.blade.php` to render the dynamic Mermaid diagram from `project->spec_content`.
- [x] 3.2 Refine the JavaScript logic in `compliance.blade.php` to accurately color nodes (GREEN/RED) based on the latest `node_status`.
- [x] 3.3 Update the "AI Audit Insights" section to display the `missing_requirements` list from the database.

## 4. Verification

- [ ] 4.1 Trigger a test GitHub webhook and verify that `ProcessAuditJob` correctly performs the audit using OpenAI.
- [ ] 4.2 Verify that the compliance dashboard reflects the correct colors on the flowchart nodes.

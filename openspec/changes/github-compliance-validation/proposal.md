## Why

Currently, the system can generate Mermaid flowcharts from PRDs, but it lacks a way to validate if the actual code implementation (via GitHub pushes) matches those specifications. Developers need real-time feedback on implementation compliance to ensure the code adheres to the defined system architecture and requirements.

## What Changes

- Implement GitHub push validation against the stored PRD content.
- Enhance the AI engine to compare code diffs with PRD requirements.
- Update the `compliance` dashboard to show detailed validation results (what's missing or wrong).
- Dynamically color Mermaid flowchart nodes: GREEN for compliant, RED for missing or non-compliant features.
- Implement the "Compliance View" functionality to provide a real-time status of project requirements.

## Capabilities

### New Capabilities
- `github-push-validation`: Automated validation of code diffs against PRD requirements triggered by GitHub webhooks.
- `compliance-dashboard-enhancement`: Real-time visualization of compliance status on the dashboard, including color-coded flowchart nodes.

### Modified Capabilities
- `dashboard-core`: Update the core dashboard logic to handle and display compliance audit results.

## Impact

- `App\Http\Controllers\WebhookController`: Enhanced logic to trigger compliance audits.
- `App\Jobs\ProcessAuditJob`: AI prompt tuning to compare code with PRD and output node-level status.
- `resources/views/compliance.blade.php`: Dynamic rendering of flowchart colors and audit insights.
- `database/migrations`: Possible addition of fields to store detailed node-level compliance status if needed (or store in `result_json`).

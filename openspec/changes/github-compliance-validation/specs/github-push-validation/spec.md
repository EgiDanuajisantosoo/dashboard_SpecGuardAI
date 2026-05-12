## ADDED Requirements

### Requirement: AI-Driven Code Compliance Audit
The system SHALL use OpenAI to perform a compliance audit on every GitHub push event. The audit MUST compare the provided code diff against the stored PRD (`prd_content`) and Mermaid specification (`spec_content`).

#### Scenario: Successful audit generation
- **WHEN** a GitHub push webhook is received and `ProcessAuditJob` is executed
- **THEN** the system SHALL send the PRD, Spec, and Diff to OpenAI and store the resulting compliance score and node status in the `audits` table.

### Requirement: Node-Level Status Extraction
The AI audit SHALL identify specific nodes from the Mermaid flowchart that are represented in the code diff and determine their implementation status.

#### Scenario: Mapping nodes to status
- **WHEN** the AI processes the diff
- **THEN** it MUST return a JSON object containing a `node_status` map where keys are Mermaid node labels and values are booleans (true for compliant/implemented, false for missing/broken).

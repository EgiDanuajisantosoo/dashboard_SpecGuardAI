## ADDED Requirements

### Requirement: Dynamic Flowchart Coloring
The `compliance` view SHALL dynamically color Mermaid flowchart nodes based on the latest audit results.

#### Scenario: Nodes turned red for missing features
- **WHEN** the `compliance` view is loaded and the latest audit has `node_status` with a `false` value for a node
- **THEN** that specific node in the Mermaid diagram SHALL be colored RED.

#### Scenario: Nodes turned green for compliant features
- **WHEN** the `compliance` view is loaded and the latest audit has `node_status` with a `true` value for a node
- **THEN** that specific node in the Mermaid diagram SHALL be colored GREEN.

### Requirement: Implementation Gap Insights
The dashboard SHALL display specific descriptions of what is missing or incorrect in the implementation compared to the PRD.

#### Scenario: Displaying audit insights
- **WHEN** an audit identifies `missing_requirements`
- **THEN** these SHALL be displayed in the "AI Audit Insights & Prompts" section of the compliance dashboard.

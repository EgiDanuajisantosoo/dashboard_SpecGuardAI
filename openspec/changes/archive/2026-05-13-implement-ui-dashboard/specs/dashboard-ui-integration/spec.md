## ADDED Requirements

### Requirement: Integrate dynamic backend loop into dark-themed UI layout
The `dashboard.blade.php` SHALL use the `<x-ui::app-layout>` wrapper to maintain consistency with the rest of the application. It SHALL loop over `$projects` provided by the backend and display each project using the dark-theme UI components.

#### Scenario: Displaying project cards in dark theme
- **WHEN** the dashboard page loads with a list of projects
- **THEN** it renders project cards using the `bg-[#161616]` background, `border-[#2A2A2A]` border, and appropriate dark mode text colors.
- **THEN** it displays the project name, repository URL, and an action button to view details.

### Requirement: Restyle health score and status indicators
The health score, compliance status, and missing requirements indicators SHALL be restyled to match the dark theme and SpecGuard UI aesthetic while preserving dynamic logic.

#### Scenario: Displaying a complete health status
- **WHEN** a project has an audit with a score >= 80
- **THEN** the progress bar and status indicator use emerald/green colors within the dark layout context.

#### Scenario: Displaying missing requirements
- **WHEN** a project's audit result contains `missing_requirements`
- **THEN** the missing requirements are listed in a styled dark-theme alert box (`bg-red-900/20` and `border-red-900/50`).

### Requirement: Preserve Mermaid diagram functionality
The inline Mermaid graph logic SHALL be preserved and functional within the newly integrated UI layout, adapting to dark theme styling.

#### Scenario: Rendering Mermaid diagram for a project
- **WHEN** a project contains an audit flow graph
- **THEN** the Mermaid library renders it.
- **THEN** the custom JavaScript function `updateMermaidColors()` correctly applies CSS classes to highlight complete, partial, and missing nodes.

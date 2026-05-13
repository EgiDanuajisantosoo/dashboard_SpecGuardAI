# dashboard-core Specification

## Purpose
TBD - created by archiving change core-system. Update Purpose after archive.

## Requirements

### Requirement: Dashboard Overview and Mermaid JS
Sistem SHALL menyediakan view `/` (sebagai dashboard PRD input) yang memungkinkan pengguna mengirim PRD, dan memisahkan tampilan visual Mermaid.js ke halaman khusus Live Audit Board (`/compliance`) serta Mermaid Previewer ke halaman `/mermaid-previewer`.

#### Scenario: User opens the dashboard
- **WHEN** pengguna login atau membuka root `/`
- **THEN** sistem menampilkan form input PRD dan daftar proyek terbaru dari database
- **THEN** sistem tidak lagi merender grafik Mermaid di halaman root ini melainkan mendelegasikannya ke halaman terpisah

## ADDED Requirements

### Requirement: Dashboard Overview and Mermaid JS
Sistem SHALL menyediakan view `/dashboard` yang menampilkan daftar menyeluruh semua proyek, menyorot compliance score terakhir masing-masing proyek, dan menginjeksikan data node_status JSON ke dalam skrip Mermaid.js untuk diagram visual.

#### Scenario: User opens the dashboard
- **WHEN** pengguna login atau membuka root `/dashboard`
- **THEN** sistem meng-query data proyek beserta nilai audit terbarunya dan menampilkan status compliance di antarmuka grafis

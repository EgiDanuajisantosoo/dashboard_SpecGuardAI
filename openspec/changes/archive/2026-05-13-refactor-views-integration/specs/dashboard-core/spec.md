## MODIFIED Requirements

### Requirement: Dashboard Overview and Mermaid JS
Sistem SHALL menyediakan view `/dashboard` yang terintegrasi penuh dengan desain UI frontend terbaru. View ini menampilkan daftar menyeluruh semua proyek, menyorot compliance score terakhir masing-masing proyek, dan menginjeksikan data node_status JSON ke dalam skrip Mermaid.js untuk diagram visual di dalam struktur UI yang baru.

#### Scenario: User opens the dashboard
- **WHEN** pengguna login atau membuka root `/dashboard`
- **THEN** sistem meng-query data proyek beserta nilai audit terbarunya
- **THEN** sistem menampilkan status compliance dan grafik mermaid di antarmuka grafis yang menggunakan layout UI terintegrasi (bukan dummy view)

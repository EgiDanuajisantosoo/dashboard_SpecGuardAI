## MODIFIED Requirements

### Requirement: Dashboard Overview and Mermaid JS
Sistem SHALL menyediakan view `/dashboard` yang menampilkan daftar menyeluruh semua proyek, menyorot compliance score terakhir masing-masing proyek, dan menginjeksikan data node_status JSON ke dalam skrip Mermaid.js untuk diagram visual.

#### Scenario: User opens the dashboard
- **WHEN** pengguna login atau membuka root `/dashboard`
- **THEN** sistem meng-query data proyek beserta nilai audit terbarunya dan menampilkan status compliance di antarmuka grafis

## ADDED Requirements

### Requirement: Compliance Route Visualization
Sistem SHALL menyediakan route `/compliance` yang merender diagram Mermaid dari `spec_content` proyek dan memberikan indikasi visual (warna) pada setiap node berdasarkan `node_status` hasil audit terakhir.

#### Scenario: Viewing compliance map
- **WHEN** pengguna mengakses `/compliance` untuk sebuah proyek
- **THEN** sistem mengambil `spec_content` dan `result_json` audit terbaru, merender flowchart, dan menerapkan warna hijau (compliant) atau merah (missing/error) pada node yang sesuai.

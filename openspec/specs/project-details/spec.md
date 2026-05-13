# project-details Specification

## Purpose
Menyediakan visibilitas mendalam terhadap status kepatuhan (compliance) proyek melalui dasbor interaktif yang menggabungkan visualisasi proses dan metrik audit.

## Requirements
### Requirement: Compliance Dashboard View
Sistem SHALL menyediakan view Blade di rute `/project/{id}` (dan `/compliance`) yang merender dasbor kepatuhan proyek. Dasbor ini harus mencakup metrik KPI, peta kepatuhan visual, dan riwayat audit.

#### Scenario: Viewing project compliance dashboard
- **WHEN** pengguna membuka detail proyek melalui rute `/project/{id}`
- **THEN** sistem merender halaman "Compliance Map" yang menampilkan Health Score, Compliance Gaps, Audited Commits, dan AI Recommendations.

### Requirement: Workflow Compliance Map (Mermaid)
Sistem SHALL merender diagram alir Mermaid.js yang merepresentasikan spesifikasi proyek. Node dalam diagram harus diwarnai secara dinamis berdasarkan hasil audit AI terbaru (Hijau: Terimplementasi, Merah/Kuning: Masalah ditemukan).

#### Scenario: Visualizing compliance status
- **WHEN** halaman dasbor kepatuhan dimuat
- **THEN** sistem menginjeksikan data `spec_content` proyek ke dalam komponen Mermaid.js
- **THEN** sistem menerapkan pewarnaan node secara asinkron menggunakan skrip JavaScript berdasarkan `result_json` dari audit terbaru.

### Requirement: Audit History and Insights
Sistem SHALL menampilkan daftar commit terbaru yang telah diaudit beserta skor dan statusnya, serta panel detail "Feature Status" yang berisi temuan audit AI (Found, Issue, Fix).

#### Scenario: Reviewing audit findings
- **WHEN** pengguna melihat panel "Feature Status" di dasbor
- **THEN** sistem menampilkan daftar fitur yang terdeteksi oleh AI beserta status klasifikasinya (implemented, partial, missing) dan rekomendasi perbaikan teknis.

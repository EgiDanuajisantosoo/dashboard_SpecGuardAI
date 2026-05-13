## MODIFIED Requirements

### Requirement: Compliance Dashboard View
Sistem SHALL menyediakan view Blade di rute `/project/{id}` (dan `/compliance`) yang merender dasbor kepatuhan proyek menggunakan tata letak dua kolom (two-column grid) pada layar besar. Kolom kiri dikhususkan untuk visualisasi proses (Mermaid), sedangkan kolom kanan berisi tumpukan informasi pendukung (AI Prompt, Commits, Feature Status).

#### Scenario: Viewing project compliance dashboard
- **WHEN** pengguna membuka detail proyek melalui rute `/project/{id}` pada layar desktop (large screen)
- **THEN** sistem merender halaman "Compliance Map" dengan tatanan baris yang membagi layar secara horizontal.
- **THEN** sistem menampilkan "Rekomendasi Prompt AI" di posisi teratas kolom informasi sebelah kanan untuk kemudahan akses developer.
- **THEN** sistem menampilkan "Recent Commits" dan "Feature Status" di bawah prompt AI pada kolom yang sama.

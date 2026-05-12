## ADDED Requirements

### Requirement: Project Detail View and Spec
Sistem SHALL menyediakan view Blade (halaman HTML) di rute `/project/{id}` yang merender secara spesifik nama proyek, URL repositori, blok read-only yang menampilkan teks YAML spesifikasi, dan tabel riwayat audit historis.

#### Scenario: Viewing a project
- **WHEN** user membuka URL detail proyek yang valid di browser
- **THEN** sistem merender halaman yang menampilkan teks OpenSpec secara terformat beserta tabel riwayat skor audit sebelumnya

## MODIFIED Requirements

### Requirement: Dashboard UI Elements
Sistem SHALL menampilkan elemen-elemen UI di halaman dashboard, termasuk daftar project, nilai compliance score, status badge, mermaid diagram, dan panel rekomendasi prompt. Selain elemen yang sudah ada, sistem juga SHALL menyediakan elemen "Add Project Button" (atau trigger sejenis) yang berfungsi untuk menampilkan form pembuatan project baru tanpa meninggalkan halaman dashboard.

#### Scenario: User views the dashboard
- **WHEN** user mengakses endpoint `/dashboard`
- **THEN** sistem menampilkan daftar project beserta tombol "Add Project" yang jelas terlihat di antarmuka utama

#### Scenario: User clicks the Add Project button
- **WHEN** user mengklik tombol "Add Project"
- **THEN** sistem menampilkan modal/form input untuk mengizinkan user menginput spesifikasi project baru (sebagaimana diuraikan di spec `add-project`)

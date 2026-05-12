## ADDED Requirements

### Requirement: Audit History JSON API
Sistem SHALL menyediakan endpoint GET `/api/project/{id}/audits` yang menyajikan data paginasi riwayat audit suatu proyek spesifik dalam format JSON terstruktur untuk konsumsi eksternal atau dashboard dynamic loading.

#### Scenario: API request with valid project ID
- **WHEN** request GET dikirim ke `/api/project/{id}/audits` dengan ID proyek yang ada di database
- **THEN** sistem mengembalikan response HTTP 200 berisi data audit (skor, status, JSON result) dengan format pagination

#### Scenario: API request with invalid project ID
- **WHEN** request GET dikirim ke `/api/project/{id}/audits` dengan ID yang tidak ditemukan
- **THEN** sistem mengembalikan HTTP 404 Not Found dengan struktur pesan error baku

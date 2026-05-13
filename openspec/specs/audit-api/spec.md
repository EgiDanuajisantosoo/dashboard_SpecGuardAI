# audit-api Specification

## Purpose
Menyediakan antarmuka pemrograman (API) untuk mengakses data riwayat audit secara terstruktur, memungkinkan pemuatan data dinamis pada dasbor atau integrasi dengan sistem eksternal.

## Requirements
### Requirement: Audit History JSON API
Sistem SHALL menyediakan endpoint GET `/api/project/{project}/audits` yang menyajikan data paginasi riwayat audit suatu proyek spesifik dalam format JSON terstruktur.

#### Scenario: API request with valid project ID
- **WHEN** request GET dikirim ke `/api/project/{id}/audits` dengan ID proyek yang ada di database
- **THEN** sistem mengembalikan response HTTP 200 berisi data audit (skor, status, JSON result) dengan format pagination Laravel standar.

#### Scenario: API request with invalid project ID
- **WHEN** request GET dikirim ke `/api/project/{id}/audits` dengan ID yang tidak ditemukan
- **THEN** sistem mengembalikan HTTP 404 Not Found.

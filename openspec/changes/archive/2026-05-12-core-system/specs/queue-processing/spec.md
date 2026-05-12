## ADDED Requirements

### Requirement: Async ProcessAuditJob
Sistem SHALL menyediakan job class `ProcessAuditJob` yang beroperasi secara asynchronous via Redis queue untuk menangani komunikasi dengan FastAPI AI Engine, mengurai response JSON, dan menyimpannya ke database.

#### Scenario: AI Engine successfully audits code
- **WHEN** job worker menghubungi FastAPI AI engine dan mendapatkan response JSON HTTP 200 yang valid
- **THEN** sistem memparsing JSON tersebut dan menyimpan data (score, status, missing requirements) ke tabel `audits`

#### Scenario: AI Engine timeouts or fails
- **WHEN** pemanggilan HTTP ke FastAPI AI engine memakan waktu lebih dari timeout (mis. 60s) atau mengembalikan HTTP error
- **THEN** sistem menunda eksekusi dan akan me-retry job tersebut hingga batas maksimal (mis. 3 kali) sebelum ditandai gagal sepenuhnya

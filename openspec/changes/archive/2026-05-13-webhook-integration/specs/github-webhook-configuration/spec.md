## ADDED Requirements

### Requirement: Webhook Secret Validation Match
Sistem SHALL menolak payload yang dikirim dari pihak yang tidak memiliki `GITHUB_WEBHOOK_SECRET` yang tepat (error 401). Untuk menyelesaikan error `Invalid signature`, secret di server harus dicocokkan dengan yang ada di platform pengirim (GitHub).

#### Scenario: Valid Secret Configured
- **WHEN** user men-setting webhook secret di GitHub yang sama persis dengan `.env` di aplikasi
- **THEN** endpoint webhook mengembalikan HTTP 202 Accepted dan berhasil memproses job

## ADDED Requirements

### Requirement: GitHub Webhook Listener
Sistem SHALL menyediakan endpoint POST `/webhook/github` yang menerima payload dari GitHub push events, memvalidasi signature menggunakan HMAC-SHA256, mengekstrak URL diff, dan memasukkan (dispatch) job audit ke antrean.

#### Scenario: Valid webhook received
- **WHEN** sistem menerima request POST dengan signature GitHub yang valid dan payload dalam format JSON
- **THEN** sistem memproses payload, memasukkan job ke antrean, dan mengembalikan HTTP status 202 Accepted

#### Scenario: Invalid webhook signature
- **WHEN** sistem menerima request POST namun signature header tidak cocok dengan environment variable `GITHUB_WEBHOOK_SECRET`
- **THEN** sistem menolak request dengan mengembalikan HTTP status 401 Unauthorized

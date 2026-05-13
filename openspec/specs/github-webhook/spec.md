# github-webhook Specification

## Purpose
Menyediakan gerbang integrasi untuk menerima sinyal perubahan kode dari GitHub dan memicu proses audit secara otomatis.

## Requirements
### Requirement: GitHub Webhook Listener
Sistem SHALL menyediakan endpoint POST `/webhook/github` yang menerima payload dari GitHub push dan pull_request events, memvalidasi signature menggunakan HMAC-SHA256, dan memasukkan (dispatch) job audit ke antrean.

#### Scenario: Valid webhook received (Push)
- **WHEN** sistem menerima request POST dengan event `push` dan signature GitHub yang valid
- **THEN** sistem mencari proyek yang cocok berdasarkan URL repositori, mengekstrak hash commit, dan memasukkan job audit ke antrean.
- **THEN** sistem mengembalikan HTTP status 202 Accepted.

#### Scenario: Valid webhook received (Pull Request)
- **WHEN** sistem menerima request POST dengan event `pull_request` dan signature GitHub yang valid
- **THEN** sistem mengekstrak hash commit dari head SHA pull request dan memasukkan job audit ke antrean.

#### Scenario: Invalid webhook signature
- **WHEN** sistem menerima request POST namun signature header tidak cocok dengan environment variable `GITHUB_WEBHOOK_SECRET`
- **THEN** sistem menolak request dengan mengembalikan HTTP status 401 Unauthorized.

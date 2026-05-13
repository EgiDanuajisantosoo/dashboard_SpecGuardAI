# queue-processing Specification

## Purpose
Menangani analisis kepatuhan kode secara asinkron dengan mengintegrasikan data dari GitHub dan menggunakan Kecerdasan Buatan (AI) untuk audit otomatis.

## Requirements
### Requirement: Async ProcessAuditJob
Sistem SHALL menyediakan job class `ProcessAuditJob` yang beroperasi secara asynchronous via antrean (Laravel Queue) untuk mengambil konten kode dari GitHub, menghubungi AI Engine (Gemini/OpenAI), dan menyimpan hasil audit.

#### Scenario: AI Engine successfully audits code
- **WHEN** job worker memproses audit untuk commit tertentu
- **THEN** sistem mengambil snapshot kode atau diff dari GitHub API
- **THEN** sistem mengirimkan konteks PRD, Spesifikasi, dan Kode ke AI Engine
- **THEN** sistem memparsing response JSON dari AI dan menyimpan data (score, status, detailed reviews) ke tabel `audits`

### Requirement: GitHub Integration & Code Fetching
Job worker MUST mampu mengambil konten kode dari repositori GitHub menggunakan GitHub API (Trees/Blobs) atau fallback ke URL diff jika terkena pembatasan (rate limit).

#### Scenario: GitHub API is rate limited
- **WHEN** pengambilan snapshot kode melalui API Trees mengembalikan error rate limit
- **THEN** sistem melakukan fallback dengan mengambil konten `.diff` mentah dari URL commit GitHub untuk tetap bisa melakukan analisis.

### Requirement: AI Analysis Error Handling & Retries
Sistem SHALL menangani kegagalan pemanggilan API AI dengan strategi backoff yang sesuai, terutama untuk menangani error rate limit (HTTP 429).

#### Scenario: AI Engine rate limits or timeouts
- **WHEN** pemanggilan API AI mengembalikan error rate limit atau timeout
- **THEN** sistem menandai status audit sebagai 'pending' dan melempar kembali eksepsi agar job di-retry oleh antrean dengan waktu tunggu (backoff) yang meningkat progresif.

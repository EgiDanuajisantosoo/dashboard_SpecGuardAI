## Why
Saat ini, spesifikasi lengkap untuk sistem inti SpecGuard AI (seperti GitHub Webhook, Queue Processing, Audit API, Project Details, dan fitur utama Dashboard) sudah didefinisikan secara matang dalam file YAML di `openspec/specs/`. Namun, fitur-fitur tersebut belum diimplementasikan ke dalam source code. Untuk menjadikan sistem ini fungsional sepenuhnya dan memenuhi target MVP (Hackathon Edition), kita perlu mengimplementasikan komponen-komponen ini secara terintegrasi.

## What Changes
- Mengimplementasikan endpoint webhook `/webhook/github` untuk menerima event push, memvalidasi signature, dan memicu antrean.
- Membangun sistem `ProcessAuditJob` berbasis Redis untuk memproses payload secara asinkron (mengirim data ke FastAPI AI Engine dan menyimpan response).
- Membangun endpoint Audit API `/api/project/{id}/audits` untuk memberikan respons data JSON terkait riwayat audit.
- Membangun UI Dashboard utama yang menampilkan list project dan visualisasi compliance (Mermaid.js diagram).
- Membangun UI halaman Project Details `/project/{id}` yang menampilkan audit history dan file konfigurasi YAML secara lengkap.

## Capabilities

### New Capabilities
- `github-webhook`: Kemampuan menerima event webhook dari GitHub secara aman, memvalidasi HMAC signature, dan meneruskannya ke background queue.
- `queue-processing`: Kemampuan memproses job audit di background, yang menghubungkan Laravel dengan FastAPI AI engine dan menangani storage serta error handling.
- `audit-api`: Kemampuan menyajikan data audit (riwayat, skor compliance, missing requirements) kepada klien dalam format JSON dengan dukungan paginasi.
- `project-details`: Kemampuan menampilkan keseluruhan rekam jejak (history) audit untuk satu project secara mendetail.
- `dashboard-core`: Kemampuan dasar dashboard untuk merender summary seluruh project secara real-time termasuk pewarnaan dinamis Mermaid diagram.

### Modified Capabilities
- (Kosong: ini adalah implementasi sistem pondasi inti/MVP awal, tidak memodifikasi capability lama).

## Impact
- **Backend (Laravel)**: Pembuatan beberapa Controller baru, Queue Job class, dan definisi route yang lengkap (web & API).
- **Database**: Membutuhkan implementasi migrations untuk tabel `projects` dan `audits` yang komprehensif.
- **Frontend (Blade)**: Penyusunan layout utama, styling menggunakan TailwindCSS, dan scripting untuk merender Mermaid.js pada browser klien.
- **Infrastructure**: Pekerjaan ini mewajibkan koneksi Redis berjalan stabil dan worker queue (e.g. `php artisan queue:work`) untuk terus memantau antrean.

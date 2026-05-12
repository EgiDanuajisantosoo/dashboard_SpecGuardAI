## Context
SpecGuard AI dirancang sebagai auditor berbasis Git workflow. Semua spesifikasi dan kebutuhan telah dirancang di dokumen `openspec/specs/` namun sistem secara fisik belum eksis. Arsitektur teknis menuntut kita untuk membangun fondasi end-to-end: mulai dari penerimaan HTTP dari GitHub Webhook, pendelegasian proses ke Redis Queue, interaksi API ke AI Engine eksternal (FastAPI), serta rendering dashboard.

## Goals / Non-Goals

**Goals:**
- Mengimplementasikan alur masuk (ingestion) via Webhook secara aman dengan memverifikasi signature HMAC-SHA256 dari GitHub.
- Memisahkan proses pemanggilan AI (yang lambat) dari request utama dengan memanfaatkan arsitektur Laravel Queue + Redis.
- Menyediakan struktur database yang efisien untuk melacak status "compliance" per commit dari setiap proyek.
- Menyajikan visualisasi dinamis di dashboard menggunakan Mermaid.js yang parameternya (warna/status) bergantung pada respons JSON AI.

**Non-Goals:**
- Autentikasi multi-tenant yang rumit (RBAC) pada web dashboard; sistem MVP berasumsi dashboard diakses secara privat oleh tim internal.
- Penulisan ulang dari awal AI engine Python (FastAPI). Desain ini hanya mengatur bagaimana aplikasi utama (Laravel) berinteraksi dengan API tersebut.

## Decisions
- **Webhook Handling**: Menggunakan `WebhookController` khusus yang mem-bypass CSRF token untuk route `/webhook/github` (diatur di konfigurasi framework), memvalidasi header signature, dan mengembalikan `HTTP 202 Accepted` segera setelah men-dispatch job.
- **Background Processing**: Dibuat class `ProcessAuditJob` (mengimplementasikan `ShouldQueue`). Job ini akan mengatur proses HTTP client request ke AI Engine dengan timeout panjang (e.g. 60-300 detik) dan melakukan retry maksimal 3 kali jika terjadi network failure.
- **Database Schema**: 
  - `projects`: `id`, `name`, `repo_url`, `spec_content`, timestamps.
  - `audits`: `id`, `project_id`, `commit_hash`, `score`, `status`, `result_json` (JSON field di Postgres), `created_at`.
- **Frontend Diagramming**: Rendering Mermaid.js dieksekusi secara asinkron di browser klien. Controller akan mengirim payload `node_status` ke Blade, dan skrip Alpine.js/VanillaJS akan men-generate ulang deklarasi `classDef` Mermaid untuk merubah warna node (Hijau, Kuning, Merah).

## Risks / Trade-offs
- **Risk (AI Engine API Timeout)**: Panggilan API ke model LLM melalui FastAPI dapat memakan waktu lama (lebih dari 1 menit) untuk diff yang sangat besar.
  - **Mitigation**: Laravel Queue Job diset dengan attribute `$timeout = 300` agar tidak ter-kill otomatis oleh sistem.
- **Risk (Struktur JSON Invalid dari AI)**: AI mungkin berhalusinasi dan mengembalikan format JSON yang rusak.
  - **Mitigation**: `ProcessAuditJob` harus memvalidasi / mencoba men-decode JSON sebelum menyimpannya ke kolom `result_json`. Jika gagal, status audit diset menjadi `failed` dan mencatat exception di log sistem.

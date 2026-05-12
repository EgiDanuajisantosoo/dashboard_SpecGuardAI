## 1. Database Migrations & Models

- [x] 1.1 Buat migration untuk tabel `projects` (name, repo_url, spec_content) dan `audits` (project_id, commit_hash, score, status, result_json)
- [x] 1.2 Buat Eloquent Model `Project` dan `Audit` beserta relasi `hasMany` / `belongsTo`
- [x] 1.3 Jalankan `php artisan migrate` untuk menerapkan struktur database

## 2. GitHub Webhook Implementation

- [x] 2.1 Buat `WebhookController` dan method untuk menerima request GitHub webhook
- [x] 2.2 Daftarkan route POST `/webhook/github` dan nonaktifkan pengecekan CSRF khusus untuk route ini
- [x] 2.3 Implementasikan verifikasi header `X-Hub-Signature-256` menggunakan HMAC-SHA256
- [x] 2.4 Parse payload webhook untuk mendapatkan commit hash dan dispatch `ProcessAuditJob`

## 3. Async Queue Processing

- [x] 3.1 Buat job class `ProcessAuditJob` dengan batas timeout yang cukup panjang (mis. 300 detik)
- [x] 3.2 Implementasikan logika pemanggilan ke FastAPI AI Engine menggunakan Laravel HTTP Client di dalam `handle()`
- [x] 3.3 Parsing respons JSON dari AI Engine dan simpan ke database `audits` (tangani kondisi timeout dan exception)
- [x] 3.4 Pastikan antrean dikonfigurasi ke Redis (`QUEUE_CONNECTION=redis` di `.env`)

## 4. Core APIs

- [x] 4.1 Buat endpoint GET `/api/project/{id}/audits` di `routes/api.php`
- [x] 4.2 Buat logic di Controller untuk mengembalikan data audit dalam bentuk paginasi JSON

## 5. Dashboard & Project Details Views

- [x] 5.1 Buat `ProjectController` untuk memproses tampilan halaman dashboard root (`/`) dan detail project (`/project/{id}`)
- [x] 5.2 Buat `dashboard.blade.php` yang menampilkan keseluruhan daftar project menggunakan TailwindCSS
- [x] 5.3 Buat `project/show.blade.php` untuk detail riwayat audit beserta tampilan read-only spesifikasi YAML
- [x] 5.4 Sisipkan script Mermaid.js di halaman detail/dashboard dan buat logic VanillaJS/Alpine untuk menginjeksi status pewarnaan diagram dari data JSON

## 6. End-to-End Test

- [x] 6.1 Lakukan simulasi pengiriman payload webhook manual menggunakan Postman atau curl
- [x] 6.2 Periksa terminal queue worker (`php artisan queue:work`) untuk memastikan job sukses dieksekusi
- [x] 6.3 Validasi hasil akhir yang di-render di antarmuka Dashboard browser

## Why

Saat ini, form input PRD di halaman Dashboard (`/`) masih berupa tampilan statis (hanya UI). Tombol "Generate OpenSpec" hanya sebuah link dummy yang mengarah ke `/openspec`. Kita perlu membuat alur nyata di mana pengguna dapat menginput PRD text, lalu sistem akan meminta AI untuk merangkumnya menjadi OpenSpec flowchart kode mermaid, menyimpannya ke database PostgreSQL, dan menampilkannya di halaman OpenSpec Review.

## What Changes

- Mengubah halaman `/` agar input "Project Area", "Target Repository", dan "Raw Text" berada di dalam sebuah form HTML `<form method="POST">`.
- Membuat endpoint POST `/project/generate` untuk memproses input tersebut.
- Controller akan memanggil layanan OpenAI (`openai-php/laravel`) untuk mengubah teks PRD menjadi struktur flowchart kode mermaid sesuai standar OpenSpec.
- Menyimpan hasil flowchart kode mermaid tersebut ke dalam tabel `projects` pada kolom `spec_content`.
- Mengarahkan ulang (redirect) pengguna ke halaman `/openspec?project_id={id}` setelah proses selesai.
- Mengubah route `/openspec` agar menggunakan controller, yang akan mengambil data project dari database dan menampilkan `spec_content` flowchart kode mermaid ke antarmuka.

## Capabilities

### New Capabilities
- `prd-to-openspec-generation`: Kemampuan untuk mengirim teks mentah PRD ke OpenAI, mengonversinya menjadi format OpenSpec flowchart kode mermaid yang terstruktur, dan menyimpan project baru.

### Modified Capabilities
- `dashboard-core`: Modifikasi spesifikasi halaman input PRD untuk menangani fungsionalitas HTTP POST dan integrasi AI.

## Impact

- **Views**: `dashboard.blade.php` (ditambah tag form dan CSRF token), `openspec.blade.php` (ditambah injeksi variabel dari database).
- **Controllers**: `DashboardController` (menangani submit PRD dan rendering view `/openspec`).
- **Database**: Menyisipkan baris baru ke tabel `projects`.
- **Integrations**: Pemanggilan API OpenAI secara sinkron atau asinkron saat submit (mengingat LLM bisa memakan waktu 5-15 detik, harus dipikirkan UX-nya, misal menampilkan loading di frontend atau redirect dengan status processing).

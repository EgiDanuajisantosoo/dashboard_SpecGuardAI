## 1. Routing & Controller Update

- [x] 1.1 Daftarkan route `POST /project/generate` pada `routes/web.php` yang mengarah ke `DashboardController@generate`.
- [x] 1.2 Ubah route `/openspec` di `routes/web.php` agar ditangani oleh Controller (`DashboardController@openspec`) dan menerima parameter `project_id`.
- [x] 1.3 Buat method `generate(Request $request)` dan `openspec(Request $request)` pada `DashboardController`.

## 2. Views Update (Frontend)

- [x] 2.1 Edit `resources/views/dashboard.blade.php`: bungkus input "Project Area", "Target Repository", dan "Raw Text" ke dalam `<form method="POST" action="/project/generate">` dan tambahkan `@csrf`.
- [x] 2.2 Edit `resources/views/openspec.blade.php`: ubah flowchart kode mermaid statis menjadi `{{ $project->spec_content }}` dan sesuaikan judul project/meta dengan data project dari database.

## 3. OpenAI Integration

- [x] 3.1 Implementasi logika di `DashboardController@generate` untuk mengambil `raw_text` PRD.
- [x] 3.2 Buat System Prompt untuk instruksi OpenAI (role auditor/arsitek, output wajib struktur flowchart kode mermaid OpenSpec).
- [x] 3.3 Gunakan Facade `OpenAI::chat()->create(...)` untuk melakukan request secara synchronous.
- [x] 3.4 Simpan data ke database: `$project = Project::create([...])` menggunakan `$request->project_area`, `$request->target_repo`, dan `$flowchart kode mermaid_content` dari AI.
- [x] 3.5 Return `redirect()->route('openspec', ['project' => $project->id])`.

## 4. Error Handling & Verification

- [x] 4.1 Tambahkan blok `try/catch` pada pemanggilan OpenAI, dengan pesan flash error jika request AI gagal (atau fallback string kosong).
- [x] 4.2 Uji coba keseluruhan alur: Ketik PRD dummy -> Submit -> AI memproses -> Redirect ke halaman `/openspec` yang menampilkan flowchart kode mermaid buatan AI.

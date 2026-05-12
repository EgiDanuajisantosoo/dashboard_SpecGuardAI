## Why
Saat ini halaman Dashboard hanya menampilkan daftar project yang sudah ada (Overview) tanpa ada cara bagi user untuk menambahkan project/repository baru. Agar SpecGuard AI dapat digunakan secara dinamis untuk berbagai repository, kita perlu menambahkan fitur untuk meng-input URL GitHub repository dan mengunggah spesifikasi OpenSpec secara langsung dari antarmuka Dashboard.

## What Changes
- Menambahkan elemen UI tombol "Add Project" di halaman Dashboard.
- Menambahkan modal/form input untuk memasukkan `project_name`, `repo_url` (GitHub), dan upload/paste `spec_content` (YAML).
- Menambahkan endpoint POST untuk menyimpan data project baru ke dalam database.
- Melakukan refresh otomatis pada daftar project setelah project baru berhasil ditambahkan.

## Capabilities

### New Capabilities
- `add-project`: Fitur untuk menambahkan GitHub repository baru beserta konfigurasi spesifikasinya ke dalam sistem melalui UI.

### Modified Capabilities
- `dashboard`: Menambahkan requirement baru `add-new-project-via-form` beserta elemen UI modal yang terkait pada spec dashboard yang sudah ada.

## Impact
- **UI/UX**: Modifikasi pada tampilan Blade/Livewire `/dashboard` dengan tambahan elemen modal form.
- **Backend API/Controller**: Penambahan route POST `/projects` untuk menerima data form dan menyimpannya ke tabel `projects`.
- **Database**: Memastikan fungsi insert dapat memasukkan `name`, `repo_url`, dan `spec_content` sesuai dengan struktur schema tabel `projects`.

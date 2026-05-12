## Context
Aplikasi SpecGuard AI saat ini menampilkan overview project di dashboard dengan status compliance-nya, tetapi tidak memiliki antarmuka (UI) untuk menambahkan project baru. Parameter seperti Project Name, GitHub Repository URL, dan OpenSpec content (YAML) belum bisa dimasukkan langsung oleh user melalui antarmuka web, sehingga sulit untuk menambahkan repository baru yang akan diaudit secara dinamis.

## Goals / Non-Goals

**Goals:**
- Membuat modal/form di halaman `/dashboard` untuk menginput project baru secara interaktif.
- Membuat API endpoint POST `/projects` untuk menerima, memvalidasi, dan menyimpan data project baru (`name`, `repo_url`, `spec_content`).
- Mengupdate daftar project di dashboard secara real-time atau dengan trigger refresh setelah form di-submit sukses.

**Non-Goals:**
- Integrasi OAuth dengan GitHub untuk list repository secara otomatis (user cukup copas URL).
- Validasi struktur logika yang sangat mendalam pada konten spesifikasi YAML saat input (fokus pada tersimpannya data mentah YAML dengan benar).

## Decisions
- **Pendekatan UI**: Menggunakan komponen Modal dengan TailwindCSS yang dipicu oleh tombol "Add Project". Pengiriman data menggunakan AJAX (jika murni Blade/JS) atau integrasi backend langsung (jika menggunakan Livewire) agar user experience lebih mulus tanpa page reload penuh.
- **Skema Database**: Form input akan memetakan field ke kolom `name`, `repo_url`, dan `spec_content` pada tabel `projects`. Model `Project` Eloquent akan menangani proses insert.
- **Format Input Spec**: Form akan menyediakan `<textarea>` untuk user mem-paste isi file `.yaml` secara langsung. Ini pendekatan tercepat untuk versi saat ini dibandingkan fitur file upload.

## Risks / Trade-offs
- **Risk (Format YAML Invalid)**: User mungkin salah format saat mem-paste konten YAML.
  - **Mitigation**: Menambahkan form request validation sederhana di Laravel untuk memastikan kolom teks tidak kosong, dan jika memungkinkan, validasi syntax parser YAML minimal.
- **Risk (URL Repository Tidak Valid)**: User menginput link yang bukan URL repository yang sah.
  - **Mitigation**: Validasi field URL (regex/url rule di Laravel validation) untuk memastikan pattern-nya sesuai dengan URL GitHub.

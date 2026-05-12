## 1. Backend API & Routing

- [ ] 1.1 Buat endpoint POST `/projects` di `routes/web.php` untuk menangani form submission
- [ ] 1.2 Implementasikan method `store` di dalam Controller (misalnya `ProjectController`)
- [ ] 1.3 Tambahkan validasi form input untuk field `name` (required), `repo_url` (required, valid URL), dan `spec_content` (required, string)
- [ ] 1.4 Simpan data yang telah divalidasi ke dalam database menggunakan model `Project`

## 2. Frontend Modal Component

- [ ] 2.1 Tambahkan tombol "Add Project" pada header/section utama di halaman `dashboard.blade.php`
- [ ] 2.2 Buat elemen UI Modal menggunakan Tailwind CSS (bisa memanfaatkan Alpine.js untuk toggle visibility modal)
- [ ] 2.3 Tambahkan form input di dalam modal (input text untuk name, input url untuk repo_url, dan textarea untuk spec_content) beserta tombol Submit dan Cancel

## 3. Integration & User Feedback

- [ ] 3.1 Pastikan form mengirimkan request POST ke `/projects` dengan CSRF token yang benar
- [ ] 3.2 Tangani respons error validasi untuk menampilkan pesan error di bawah input field yang bermasalah pada form
- [ ] 3.3 Tambahkan session flash message ("Project berhasil ditambahkan!") yang akan dirender di dashboard setelah sukses submit dan redirect
- [ ] 3.4 Lakukan pengujian end-to-end dengan mengklik tombol, mengisi form, menyimpan, dan memverifikasi project baru muncul di dashboard

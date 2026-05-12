## ADDED Requirements

### Requirement: Add Project Modal Form
Sistem SHALL menyediakan sebuah form di dalam modal (atau antarmuka sejenis) di halaman dashboard yang memungkinkan pengguna untuk menginput detail project baru. Form ini harus memuat field untuk "Project Name", "GitHub Repository URL", dan sebuah textarea untuk mem-paste isi file OpenSpec (YAML format).

#### Scenario: Successful project creation
- **WHEN** pengguna mengisi semua field yang diwajibkan dengan data yang valid dan menekan tombol simpan
- **THEN** sistem menyimpan data project tersebut ke dalam database (tabel `projects`) dan memperbarui tampilan daftar project di dashboard secara otomatis

#### Scenario: Validation error on empty fields
- **WHEN** pengguna mencoba menyimpan form dengan membiarkan field wajib (seperti nama project atau URL) kosong
- **THEN** sistem menolak penyimpanan dan menampilkan pesan error validasi di dekat field yang bermasalah

#### Scenario: Invalid repository URL format
- **WHEN** pengguna memasukkan teks yang bukan merupakan format URL yang valid ke dalam field "GitHub Repository URL"
- **THEN** sistem menampilkan pesan error validasi yang menyatakan format URL tidak valid

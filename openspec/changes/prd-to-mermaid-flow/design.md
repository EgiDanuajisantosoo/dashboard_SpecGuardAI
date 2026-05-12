## Context

Aplikasi memiliki halaman dashboard (`/`) yang digunakan sebagai antarmuka input PRD (Product Requirements Document). Saat ini form tersebut hanya tampilan (dummy) dan tombol submit-nya mengarah statis ke halaman `/openspec`. Tujuan dari desain ini adalah mengintegrasikan input pengguna dengan backend Laravel dan layanan OpenAI, sehingga setiap teks PRD akan dikonversi menjadi format OpenSpec (flowchart kode mermaid) yang valid, disimpan ke PostgreSQL, dan langsung dapat ditinjau oleh pengguna.

## Goals / Non-Goals

**Goals:**
- Membuat form submission dari halaman `/` (menggunakan POST method).
- Menyediakan endpoint POST `/project/generate` untuk menangani input form.
- Mengirimkan teks PRD ke OpenAI menggunakan `openai-php/laravel` untuk ekstraksi menjadi struktur flowchart kode mermaid OpenSpec.
- Menyimpan entitas Project baru (beserta `spec_content`-nya) ke database.
- Melakukan redirect ke `/openspec?project={id}` setelah proses selesai.

**Non-Goals:**
- Tidak memodifikasi halaman `/compliance` (Live Audit Board) pada tahapan ini.
- Tidak membangun proses background queue untuk task AI ini (karena user butuh respons instan untuk direview, request akan dieksekusi secara sinkron).

## Decisions

- **Sync vs Async AI Call**: Karena form ini menghasilkan OpenSpec yang akan langsung ditampilkan di halaman berikutnya, pemanggilan OpenAI akan dilakukan secara *Synchronous* di dalam Controller. 
  - *Risiko*: Waktu loading bisa memakan 5-15 detik.
  - *Mitigasi*: Menambahkan state loading (spinner/tulisan "Generating OpenSpec...") pada frontend dengan JS saat submit.
- **OpenAI Model**: Menggunakan model `gpt-4o-mini` (atau model default sesuai `.env`) karena format flowchart kode mermaid lebih mudah dihasilkan dan efisiensi biayanya lebih baik untuk operasi string teks sedang. Prompt yang sangat ketat (System Prompt) wajib diterapkan agar output bersih, tanpa format flowchart kode mermaid tambahan.
- **Error Handling**: Jika respons API OpenAI gagal atau gagal menghasilkan flowchart kode mermaid valid, aplikasi akan *fallback* menyimpan `spec_content` dengan string error, atau me-redirect kembali ke form dengan pesan flash error.

## Risks / Trade-offs

- **Risk: Timeout Request** → Waktu eksekusi Laravel mungkin *timeout* jika AI butuh waktu lebih dari 60 detik. *Mitigasi*: Set `Http::timeout()` dan limit `max_execution_time` khusus di dalam Controller method tersebut jika memungkinkan.
- **Risk: Parsing JSON dari LLM** → AI mungkin menyisipkan teks ````flowchart kode mermaid`. *Mitigasi*: Parsing string sebelum menyimpannya ke database untuk menghilangan *code blocks* flowchart kode mermaid.

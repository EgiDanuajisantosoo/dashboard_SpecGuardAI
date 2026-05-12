## Context
Saat ini sistem telah mengimplementasikan validasi HMAC-SHA256 untuk endpoint `/webhook/github` melalui Controller. Masalahnya adalah GitHub belum dikonfigurasi dengan secret key yang sama dengan yang ada di file `.env` sistem.

## Goals / Non-Goals

**Goals:**
- Membuat sebuah token keamanan acak untuk digunakan sebagai secret webhook.
- Menambahkan token tersebut ke GitHub Repository Webhook Settings.
- Memperbarui file `.env` di lokal dengan token yang sama.

**Non-Goals:**
- Mengubah arsitektur keamanan (tetap menggunakan HMAC-SHA256).
- Mengubah sistem antrean / AI backend.

## Decisions
- Kita akan membuat tugas (task) untuk men-generate string rahasia secara otomatis, lalu memandu pengguna memperbarui GitHub mereka.
- Ini hanyalah penyelarasan environment (Infrastructure as Code level) tanpa perlu mengubah logic aplikasi.

## Risks / Trade-offs
- **Risk (Lupa memperbarui di sisi GitHub)**: Jika hanya .env yang diperbarui, webhook tetap gagal.
  - **Mitigation**: Langkah tasks.md akan memandu pengguna secara eksplisit.

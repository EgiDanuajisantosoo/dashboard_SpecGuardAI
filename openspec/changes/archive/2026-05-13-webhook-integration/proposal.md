## Why
Pengguna telah berhasil mengonfigurasi Ngrok untuk mengekspos aplikasi ke internet, namun saat GitHub mengirim webhook, respons yang diterima adalah `{"error":"Invalid signature"}`. Hal ini terjadi karena nilai `GITHUB_WEBHOOK_SECRET` di environment variable belum disamakan dengan field "Secret" di halaman Webhook GitHub. Perubahan ini bertujuan untuk memandu konfigurasi tersebut agar webhook end-to-end berfungsi dengan baik.

## What Changes
- Menyelaraskan secret webhook antara `.env` Laravel dengan konfigurasi GitHub.
- Memverifikasi endpoint webhook `POST /webhook/github` agar menerima payload GitHub dengan sukses tanpa invalid signature error.

## Capabilities

### New Capabilities
- `github-webhook-configuration`: Dokumentasi dan tugas langkah-demi-langkah terkait penyelarasan token keamanan webhook GitHub.

### Modified Capabilities
- (Kosong)

## Impact
- **Backend**: Hanya modifikasi konfigurasi `.env`.
- **Infrastructure**: Memastikan GitHub dapat mempercayai server melalui verifikasi secret HMAC-SHA256.

<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are an expert system architect and auditor. Your task is to convert raw Product Requirements Documents (PRD) into a Mermaid.js flowchart code representing the system architecture or flow. Output ONLY the raw Mermaid code, without any markdown formatting blocks like ```mermaid or ```.',
            ],
            [
                'role' => 'user',
                'content' => "PRD: Simple Auth App\n## 1. Tujuan\nMembuat sistem pendaftaran dan login dasar di mana pengguna bisa membuat akun\ndan mengakses halaman Beranda yang diproteksi.\n## 2. Daftar Halaman & Fungsionalitas\nA. Halaman Registrasi (Register)\n- Kolom Input: Email, Password, dan Konfirmasi Password.\n- Aksi: Tombol \"Daftar\".\n- Syarat Berhasil (Acceptance Criteria):\no Sistem mengecek email apakah sudah terdaftar.\no Password dan Konfirmasi Password harus sama.\no Jika sukses, data tersimpan (password di-hash / disandikan) dan\npengguna diarahkan ke Halaman Login.\nB. Halaman Masuk (Login)\n- Kolom Input: Email dan Password.\n- Aksi: Tombol \"Masuk\".\n- Syarat Berhasil (Acceptance Criteria):\no Sistem mencocokkan Email dan Password dengan database.\no Jika salah, muncul pesan error.\no Jika benar, sesi pengguna (token) dibuat dan pengguna diarahkan ke\n## Halaman Beranda.\nC. Halaman Beranda (Home)\n- Tampilan: Teks sapaan \"Selamat Datang!\" dan tombol \"Logout\".\n- Syarat Berhasil (Acceptance Criteria):\no Proteksi: Halaman ini wajib mengecek sesi login. Jika pengguna belum\nlogin, langsung lempar kembali ke Halaman Login.\no Logout: Jika tombol \"Logout\" ditekan, sesi dihapus dan pengguna\ndikembalikan ke Halaman Login.",
            ],
        ],
        'max_tokens' => 1500,
    ]);
    echo "SUCCESS\n";
    echo $response->choices[0]->message->content;
} catch (\Exception $e) {
    echo "ERROR: " . get_class($e) . "\n";
    echo $e->getMessage() . "\n";
}

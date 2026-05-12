<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Latest 5 Audits (full detail) ===\n";
$audits = App\Models\Audit::latest()->take(5)->get();
foreach ($audits as $a) {
    echo "\nAudit ID={$a->id} | project_id={$a->project_id} | status={$a->status} | score={$a->score}\n";
    echo "  commit_hash: {$a->commit_hash}\n";
    echo "  result_json type: " . gettype($a->result_json) . "\n";
    echo "  result_json raw: " . json_encode($a->result_json) . "\n";
    echo "  created_at: {$a->created_at}\n";
    echo "  updated_at: {$a->updated_at}\n";
}

echo "\n=== Laravel Log (last 50 lines) ===\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = file($logFile);
    $last = array_slice($lines, -50);
    echo implode('', $last);
} else {
    echo "No log file found.\n";
}

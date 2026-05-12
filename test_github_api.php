<?php
$owner = 'EgiDanuajisantosoo';
$repo = 'test-project';
$sha = '8063256555c1a12a2e7d32b9678e68f62cbf43fc';

$ch = curl_init("https://api.github.com/repos/{$owner}/{$repo}/git/trees/{$sha}?recursive=1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: SpecGuardAI/1.0']);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$result = json_decode(curl_exec($ch), true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: {$httpCode}\n";

if ($httpCode !== 200) {
    echo "Error: " . json_encode($result) . "\n";
    exit;
}

$files = array_filter($result['tree'] ?? [], function($item) {
    if ($item['type'] !== 'blob') return false;
    if (!preg_match('/\.(php)$/', $item['path'])) return false;
    if (str_starts_with($item['path'], 'vendor/')) return false;
    return true;
});

echo count($files) . " PHP files found in codebase\n\n";
foreach (array_slice(array_values($files), 0, 20) as $f) {
    echo "  " . $f['path'] . "\n";
}

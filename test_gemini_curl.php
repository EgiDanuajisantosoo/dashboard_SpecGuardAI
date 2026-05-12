<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'gemini-1.5-flash',
    'messages' => [
        ['role' => 'user', 'content' => 'hello']
    ]
]));
$headers = array();
$headers[] = 'Authorization: Bearer AIzaSyDzgnEie-9fcdN6yHJBk-HlXkJx2oI0DKM';
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
echo "RAW RESPONSE:\n";
echo $result;

<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
        'model' => env('OPENAI_MODEL', 'gemini-1.5-flash'),
        'messages' => [
            [
                'role' => 'user',
                'content' => "Say hello",
            ],
        ],
    ]);
    echo "SUCCESS\n";
    echo $response->choices[0]->message->content;
} catch (\Exception $e) {
    echo "ERROR: " . get_class($e) . "\n";
    echo $e->getMessage() . "\n";
}

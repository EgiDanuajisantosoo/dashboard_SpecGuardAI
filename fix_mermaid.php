<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$projects = App\Models\Project::all();
foreach ($projects as $p) {
    $original = $p->spec_content ?? '';
    // Fix invalid Mermaid arrow syntax: -->|text|> → -->|text|
    $fixed = str_replace('|>', '|', $original);
    if ($fixed !== $original) {
        $p->update(['spec_content' => $fixed]);
        echo "Fixed project ID {$p->id}: {$p->name}\n";
        echo "Preview: " . substr($fixed, 0, 300) . "\n\n";
    } else {
        echo "Project ID {$p->id}: no fix needed\n";
    }
}
echo "Done.\n";

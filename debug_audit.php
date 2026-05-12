<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$projects = App\Models\Project::select('id','name','repo_url')->get();
foreach ($projects as $p) {
    echo "ID={$p->id} | name={$p->name} | repo_url={$p->repo_url}\n";
}
echo "\n--- Latest 3 Audits ---\n";
$audits = App\Models\Audit::with('project:id,name')->latest()->take(3)->get();
foreach ($audits as $a) {
    echo "audit_id={$a->id} | project=[{$a->project->name}] | status={$a->status} | score={$a->score}\n";
    if (isset($a->result_json['summary'])) {
        echo "  summary: " . $a->result_json['summary'] . "\n";
    }
}

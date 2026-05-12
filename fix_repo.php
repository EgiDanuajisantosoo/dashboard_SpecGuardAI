<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Update the Authentication Service project (ID=7, latest one) to use the correct repo URL
$project = App\Models\Project::find(7);
if ($project) {
    $project->repo_url = 'https://github.com/EgiDanuajisantosoo/test-project.git';
    $project->save();
    echo "Updated project ID=7 ({$project->name}) repo_url to: {$project->repo_url}\n";
} else {
    echo "Project not found!\n";
}
echo "Done.\n";

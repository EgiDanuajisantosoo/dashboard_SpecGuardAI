<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Audit;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $specContent = <<<'YAML'
feature: Create Order

endpoint:
  method: POST
  path: /api/orders

requirements:
  - authentication
  - validation
  - logging

flow:
  - user request
  - auth validation
  - input validation
  - save transaction
  - activity logging
  - json response
YAML;

        $project = Project::create([
            'name' => 'E-Commerce API',
            'repo_url' => 'https://github.com/EgiDanuajisantosoo/test-project.git',
            'spec_content' => $specContent,
        ]);

        Audit::create([
            'project_id' => $project->id,
            'commit_hash' => 'abc123def456ghi789jkl',
            'score' => 85,
            'status' => 'partial',
            'result_json' => [
                'score' => 85,
                'status' => 'partial',
                'missing_requirements' => [
                    'Error logging not properly implemented',
                    'Validation error messages incomplete',
                ],
                'node_status' => [
                    'auth' => true,
                    'validation' => true,
                    'logging' => false,
                ],
            ],
        ]);
    }
}

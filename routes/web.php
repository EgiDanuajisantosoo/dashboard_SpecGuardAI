<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('dashboard');
// });


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/project/generate', [DashboardController::class, 'generate'])->name('project.generate');
Route::get('/openspec', [DashboardController::class, 'openspec'])->name('openspec');

Route::get('/project/{project}', [DashboardController::class, 'show'])->name('project.show');
Route::get('/api/project/{project}/audits', [DashboardController::class, 'audits'])->name('api.project.audits');

Route::post('/webhook/github', [WebhookController::class, 'github'])->name('webhook.github');

Route::get('/compliance', function () {
    return view('compliance');
});

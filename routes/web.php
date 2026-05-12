<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/project/{project}', [DashboardController::class, 'show'])->name('project.show');
Route::get('/api/project/{project}/audits', [DashboardController::class, 'audits'])->name('api.project.audits');

Route::post('/webhook/github', [WebhookController::class, 'github'])->name('webhook.github');

Route::get('/openspec', function () {
    return view('openspec');
});

Route::get('/compliance', function () {
    return view('compliance');
});

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('ui.dashboard');
});

Route::get('/openspec', function () {
    return view('ui.openspec');
});

Route::get('/compliance', function () {
    return view('ui.compliance');
});

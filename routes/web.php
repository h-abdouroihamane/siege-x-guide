<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\OperatorController;

Route::get('/operators/all', [OperatorController::class, 'getAll']);
Route::get('/operators', [OperatorController::class, 'showAll']);
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

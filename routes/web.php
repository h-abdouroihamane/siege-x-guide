<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\SquadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/operators');
});

Route::prefix('operators')
    ->controller(OperatorController::class)
    ->group(function () {
        Route::get('/', 'showAll');
        Route::get('/all', 'getAll');
    });

Route::prefix('squads')
    ->controller(SquadController::class)
    ->group(function () {
        Route::get('/', 'showAll');
        Route::get('/all', 'getAll');
    });

Route::prefix('admin')
    ->controller(AdminController::class)
    ->group(function () {
        Route::get('/login', 'index');
    });

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

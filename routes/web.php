<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\SquadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/operators');
});

Route::prefix('operators')
    ->name('operator.')
    ->controller(OperatorController::class)
    ->group(function () {
        Route::get('/', 'showAll')->name('show');
        Route::get('/all', 'getAll')->name('get');
    });

Route::prefix('operators')
    ->controller(OperatorController::class)
    ->name('operator.')
    ->middleware('auth')
    ->group(function() {
        Route::get('/select', 'selectForEditing')->name('selectForEditing');
        Route::post('/select', 'selectPost')->name('selectPost');
        Route::get('/edit/{operator}', 'edit')->name('edit');
        Route::post('/update/{operatorId}', 'update')->name('update');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
    });

Route::prefix('squads')
    ->controller(SquadController::class)
    ->group(function () {
        Route::get('/', 'showAll');
        Route::get('/all', 'getAll');
    });

Route::prefix('admin')
    ->name('admin.')
    ->controller(AdminController::class)
    ->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'authenticate');
    });

Route::middleware('auth')
    ->name('admin.')
    ->prefix('admin')
    ->controller(AdminController::class)
    ->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
    });

// require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

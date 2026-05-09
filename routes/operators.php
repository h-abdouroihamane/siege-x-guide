<?php
use App\Http\Controllers\OperatorController;
use Illuminate\Support\Facades\Route;

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
    ->group(function () {
        Route::get('/select', 'selectForEditing')->name('selectForEditing');
        Route::get('/edit/{operator}', 'edit')->name('edit');
        Route::post('/update/{operatorId}', 'update')->name('update');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
    });

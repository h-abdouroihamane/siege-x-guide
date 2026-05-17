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

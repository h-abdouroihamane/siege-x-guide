<?php
use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;

Route::controller(AboutController::class)
    ->name('about.')
    ->prefix('about')
    ->group(function() {
        Route::get('/', 'index')->name('index');
});


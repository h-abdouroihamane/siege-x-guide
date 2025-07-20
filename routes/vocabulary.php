<?php
use App\Http\Controllers\VocabularyController;
use Illuminate\Support\Facades\Route;

Route::controller(VocabularyController::class)
    ->name('vocabulary.')
    ->prefix('vocabulary')
    ->group(function() {
        Route::get('/', 'index')->name('index');
});


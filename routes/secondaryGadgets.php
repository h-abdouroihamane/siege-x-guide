<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecondaryGadgetController;

Route::prefix('secondary-gadgets')
    ->controller(SecondaryGadgetController::class)
    ->group(function () {
        Route::get('/', 'showAll');
        Route::get('/all', 'getAll');
    });

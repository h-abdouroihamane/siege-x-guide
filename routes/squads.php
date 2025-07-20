<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SquadController;

Route::prefix('squads')
    ->controller(SquadController::class)
    ->group(function () {
        Route::get('/', 'showAll');
        Route::get('/all', 'getAll');
    });



<?php

use App\Http\Controllers\OperatorController;
use App\Http\Controllers\SquadController;
use Illuminate\Support\Facades\Route;

Route::get('/operators/all', [OperatorController::class, 'getAll']);
Route::get('/operators', [OperatorController::class, 'showAll']);
Route::get('/squads/all', [SquadController::class, 'getAll']);
Route::get('/squads', [SquadController::class, 'showAll']);
Route::get('/', function () {

    return redirect('/operators');

});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

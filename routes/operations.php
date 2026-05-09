<?php

use App\Http\Controllers\OperationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::post('/operations', [OperationController::class, 'store'])->name(
        'operation.store',
    );
});

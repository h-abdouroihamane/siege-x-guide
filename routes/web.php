<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/operators');
});

require __DIR__ . '/about.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/operators.php';
require __DIR__ . '/squads.php';
require __DIR__ . '/vocabulary.php';




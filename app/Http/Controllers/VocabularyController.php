<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Role;

class VocabularyController extends Controller
{
       public function index() {
           return Inertia::render('Vocabulary',
           ['roles' => Role::all()->toResourceCollection()->resolve()]
           );
 }
}



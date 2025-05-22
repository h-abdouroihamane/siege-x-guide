<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\OperatorResource;
use App\Models\Operator;
use Inertia\Inertia;

class OperatorController extends Controller
{
    public function getAll() {
        return OperatorResource::collection(Operator::with('roles:id,name', 'squad:id,name', 'operation:id,name')->get());
    }

    public function showAll() {
        return Inertia::render('Home', [
          'operators' => $this->getAll()
        ]);
    }
}

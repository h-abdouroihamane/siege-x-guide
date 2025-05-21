<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\OperatorResource;
use App\Models\Operator;

class OperatorController extends Controller
{
    public function getAll() {
        return OperatorResource::collection(Operator::all());
    }
}

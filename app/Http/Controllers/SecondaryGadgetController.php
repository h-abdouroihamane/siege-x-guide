<?php

namespace App\Http\Controllers;

use App\Http\Resources\SecondaryGadgetResource;
use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\SecondaryGadget;
use Inertia\Inertia;

class SecondaryGadgetController extends Controller
{
    public function getAll()
    {
        return SecondaryGadgetResource::collection(SecondaryGadget::all());
    }

    public function showAll()
    {
        return Inertia::render('SecondaryGadgetView', [
            'secondaryGadgets' => $this->getAll(),
        ]);
    }
}

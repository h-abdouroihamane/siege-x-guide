<?php

namespace App\Http\Controllers;

use App\Http\Resources\SecondaryGadgetResource;
use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\Operation;
use App\Models\SecondaryGadget;
use Inertia\Inertia;

class SecondaryGadgetController extends Controller
{
    public function getGadgets(string $side)
    {
        return SecondaryGadgetResource::collection(
            SecondaryGadget::where('side', $side)->get(),
        );
    }

    public function getOperators(string $side)
    {
        return Operator::where('side', $side)->orderBy('name')->pluck('name');
    }

    public function getMostRecentOperation()
    {
        return Operation::orderBy('year', 'desc')
            ->orderBy('season', 'desc')
            ->first();
    }

    public function showAll()
    {
        $operation = $this->getMostRecentOperation();

        return Inertia::render('SecondaryGadgetView', [
            'attackGadgets' => $this->getGadgets('Attack'),
            'defenseGadgets' => $this->getGadgets('Defense'),
            'attackers' => $this->getOperators('Attack'),
            'defenders' => $this->getOperators('Defense'),
            'operationName' => $operation->name,
            'year' => $operation->year,
            'season' => $operation->season,
        ]);
    }
}

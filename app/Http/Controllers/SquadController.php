<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Squad;
use App\Models\Operator;
use App\Models\Operation;
use Inertia\Inertia;

class SquadController extends Controller
{
    public function getAll()
    {
        $squads = [];

        foreach (
            Squad::with('operators:id,name')->orderBy('rank')->get()
            as $sq
        ) {
            $operators = [];

            foreach ($sq->operators as $op) {
                $operators[] = $op->name;
            }

            $squads[$sq->name] = $operators;
        }

        return $squads;
    }

    public function getMostRecentOperation()
    {
        return Operation::mostRecent()->first();
    }

    public function showAll()
    {
        $operation = $this->getMostRecentOperation();
        return Inertia::render('SquadsView', [
            'squads' => $this->getAll(),
            'operationName' => $operation->name,
            'year' => $operation->year,
            'season' => $operation->season,
        ]);
    }
}

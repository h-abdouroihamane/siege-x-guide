<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Squad;
use App\Models\Operator;
use App\Models\Operation;
use Inertia\Inertia;

class SquadController extends Controller
{
    public function getUnaffiliatedOperators() {
        $unaffil = [];

        foreach(Operator::doesntHave('squad')->orderBy('id', 'asc')->get() as $op) {
            $unaffil[] = $op->name;
        }

        return $unaffil;
    }

    public function getAll()  {
        $squads = [];

        foreach (Squad::with('operators:id,name')->get() as $sq) {
            $operators = [];

            foreach($sq->operators as $op) {
                $operators[] = $op->name;
            }

            $squads[$sq->name] = $operators;
        }

        $squads['Unaffiliated'] = $this->getUnaffiliatedOperators();

        return $squads;
    }

    public function getMostRecentOperation() {
        return Operation::orderBy('year', 'desc')
            ->orderBy('season', 'desc')
            ->first();
    }

    public function showAll()
    {
        $operation = $this->getMostRecentOperation();
        return Inertia::render('SquadsView', [
            'squads' => $this->getAll(),
            'operationName' => $operation->name,
            'year' => $operation->year,
            'season' => $operation->season
        ]);
    }
}

<?php
namespace App\Http\Controllers;

use App\Http\Resources\OperatorResource;
use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\Squad;
use Inertia\Inertia;

class OperatorController extends Controller
{
    public function getAll()
 {
        return OperatorResource::collection(Operator::with('roles:id,name', 'squad:id,name', 'operation:id,name')->get());
    }

    public function showAll()
    {
        return Inertia::render('OperatorsView', [
            'operators' => $this->getAll(),
        ]);
    }

  public function selectForEditing() {
        return Inertia::render('OperatorSelection', ['operators' => Operator::pluck('name')->sort()]);
    }


    public function selectPost(Request $request) {
        $operatorName = $request->input('operatorName');
        $operator = new OperatorResource(Operator::where('name', $operatorName)->with('roles:id,name', 'squad:id,name')->first());
        $squads = Squad::pluck('name')->sort();

        if (is_null($operator)) {
            return to_route('operator.selectForEditing');
        }

        return Inertia::render('OperatorForm',
            ['operator'=> $operator,
                'squads' => $squads,
            'mode' => 'edit']);
    }
}

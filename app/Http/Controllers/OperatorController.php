<?php
namespace App\Http\Controllers;

use App\Http\Resources\OperatorResource;
use App\Http\Resources\OperationResource;
use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\Operation;
use App\Models\QueerIdentity;
use App\Models\Squad;
use Inertia\Inertia;
use App\Http\Requests\OperatorRequest;

class OperatorController extends Controller
{
    public function getAll()
    {
        return OperatorResource::collection(
            Operator::with(
                'roles:id,name',
                'squad:id,name',
                'operation:id,name,release_date',
                'queerIdentities:id,name'
            )
            ->get()
        );
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
        $operator = Operator::where('name', $operatorName)
                ->with('roles:id,name', 'squad:id,name', 'operation:id,name,release_date', 'queerIdentities:id,name')
                ->first();

        if (is_null($operator)) {
            return to_route('operator.selectForEditing');
        }

        return to_route('operator.edit', ['operator' => $operator]);
    }

    public function edit(Operator $operator) {
        $operatorResource = new OperatorResource($operator);
        $squads = Squad::pluck('name')->sort();
        $operations = OperationResource::collection(Operation::orderByDesc('release_date')->get());
        $queerIdentities = QueerIdentity::pluck('name')->sort();

        return Inertia::render('EditOperator',
            ['operator'=> $operatorResource,
            'squads' => $squads,
            'operations' => $operations,
            'queerIdentities' => $queerIdentities,
            'submitRoute' => route('operator.update', ['operatorId' => $operator->id])
        ]);
    }
    public function update(OperatorRequest $request, string $operatorId) {
        $operator = Operator::findOrFail($operatorId);

        //Basic updates
        $operator->update(
            [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'side' => $request->input('side'),
                'operation_id' => $request->input('operation_id'),
            ]
        );

        $operator->addToSquad($request->input('squad'));

        $queerIdentities = $request->array('queerIdentities') ?? [];

        $queerIds = count($queerIdentities) > 0
            ? QueerIdentity::whereIn('name', $request->array('queerIdentities'))->pluck('id')
            : [];

        $operator->queerIdentities()->sync($queerIds);

        $filename = $operator->getCleanName() . '.png';

        if ($request->hasFile('icon')) {
            $request->file('icon')->storeAs('operatorIcons', $filename, 'public');
        }

        if ($request->hasFile('portrait')) {
            $request->file('portrait')->storeAs('operatorPortraits', $filename, 'public');
        }

        return;
    }
}

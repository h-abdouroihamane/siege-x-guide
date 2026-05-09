<?php
namespace App\Http\Controllers;

use App\Http\Resources\OperatorResource;
use App\Http\Resources\OperationResource;
use App\Http\Resources\SecondaryGadgetOptionResource;
use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\Operation;
use App\Models\Role;
use App\Models\QueerIdentity;
use App\Models\SecondaryGadget;
use App\Models\Squad;
use Inertia\Inertia;
use App\Http\Requests\EditOperatorRequest;
use App\Http\Requests\CreateOperatorRequest;

class OperatorController extends Controller
{
    public function getAll()
    {
        return OperatorResource::collection(
            Operator::with(
                'roles:id,name',
                'squad:id,name',
                'operation:id,name,release_date',
                'rework.operation:id,name,release_date',
                'queerIdentities:id,name',
            )->get(),
        );
    }

    public function showAll()
    {
        return Inertia::render('OperatorsView', [
            'operators' => $this->getAll(),
        ]);
    }

    public function selectForEditing()
    {
        return Inertia::render('OperatorSelection', [
            'operators' => Operator::pluck('name')->sort(),
        ]);
    }

    public function selectPost(Request $request)
    {
        $operatorName = $request->input('operatorName');
        $operator = Operator::where('name', $operatorName)
            ->with(
                'roles:id,name',
                'squad:id,name',
                'operation:id,name,release_date',
                'rework.operation:id,name,release_date',
                'queerIdentities:id,name',
            )
            ->first();

        if (is_null($operator)) {
            return to_route('operator.selectForEditing');
        }

        return to_route('operator.edit', ['operator' => $operator]);
    }

    public function edit(Operator $operator)
    {
        $operator->load('secondaryGadgets');
        $operatorResource = new OperatorResource($operator);
        $squads = Squad::orderBy('name')->pluck('name');
        $operations = OperationResource::collection(
            Operation::orderByDesc('release_date')->get(),
        );
        $queerIdentities = QueerIdentity::orderBy('name')->pluck('name');
        $roles = Role::pluck('name')->sort();
        $secondaryGadgets = SecondaryGadgetOptionResource::collection(
            SecondaryGadget::orderBy('name')->get(),
        );

        return Inertia::render('EditOperator', [
            'operator' => $operatorResource,
            'squads' => $squads,
            'operations' => $operations,
            'roles' => $roles,
            'queerIdentities' => $queerIdentities,
            'secondaryGadgets' => $secondaryGadgets,
            'submitRoute' => route('operator.update', [
                'operatorId' => $operator->id,
            ]),
        ]);
    }
    public function update(EditOperatorRequest $request, string $operatorId)
    {
        $operator = Operator::findOrFail($operatorId);

        //Basic updates
        $operator->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'side' => $request->input('side'),
            'operation_id' => $request->input('operation_id'),
        ]);

        $operator->addToSquad($request->input('squad'));

        $queerIdentities = $request->array('queerIdentities') ?? [];

        $queerIds =
            count($queerIdentities) > 0
                ? QueerIdentity::whereIn(
                    'name',
                    $request->array('queerIdentities'),
                )->pluck('id')
                : [];

        $operator->queerIdentities()->sync($queerIds);

        $roles = $request->array('roles') ?? [];
        $roleIds =
            count($roles) > 0 ? Role::whereIn('name', $roles)->pluck('id') : [];

        $operator->roles()->sync($roleIds);

        $operator
            ->secondaryGadgets()
            ->sync($request->input('secondary_gadget_ids', []));

        $filename = $operator->getCleanName() . '.png';

        if ($request->hasFile('icon')) {
            $request
                ->file('icon')
                ->storeAs('operatorIcons', $filename, 'public');
        }

        if ($request->hasFile('portrait')) {
            $request
                ->file('portrait')
                ->storeAs('operatorPortraits', $filename, 'public');
        }

        return to_route('admin.dashboard')->with(
            'message',
            "Operator $operator->name successfully edited !",
        );
    }

    public function create()
    {
        $squads = Squad::orderBy('name')->pluck('name');
        $queerIdentities = QueerIdentity::orderBy('name')->pluck('name');
        $roles = Role::pluck('name')->sort();
        $operations = OperationResource::collection(
            Operation::orderByDesc('release_date')->get(),
        );
        $secondaryGadgets = SecondaryGadgetOptionResource::collection(
            SecondaryGadget::orderBy('name')->get(),
        );
        return Inertia::render('CreateOperator', [
            'squads' => $squads,
            'queerIdentities' => $queerIdentities,
            'roles' => $roles,
            'operations' => $operations,
            'secondaryGadgets' => $secondaryGadgets,
            'submitRoute' => route('operator.store'),
        ]);
    }

    public function store(CreateOperatorRequest $request)
    {
        //Creating the Operation to get its ID
        $year = $request->input('year');
        $season = $request->input('season');
        $operation_id = 'Y' . $year . 'S' . $season;

        $operation = new Operation([
            'id' => $operation_id,
            'name' => $request->input('operationName'),
            'year' => $year,
            'season' => $season,
            'release_date' => $request->input('releaseDate'),
        ]);

        $operation->save();

        $operator = new Operator([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'side' => $request->input('side'),
            'year' => $year,
            'season' => $season,
            'operation_id' => $operation_id,
        ]);

        $operator->save();

        $operator->addToSquad($request->input('squad'));

        $queerIdentities = $request->array('queerIdentities') ?? [];

        $queerIds =
            count($queerIdentities) > 0
                ? QueerIdentity::whereIn(
                    'name',
                    $request->array('queerIdentities'),
                )->pluck('id')
                : [];

        $operator->queerIdentities()->sync($queerIds);

        $roles = $request->array('roles') ?? [];
        $roleIds =
            count($roles) > 0 ? Role::whereIn('name', $roles)->pluck('id') : [];

        $operator->roles()->sync($roleIds);

        $operator
            ->secondaryGadgets()
            ->sync($request->input('secondary_gadget_ids', []));

        $filename = $operator->getCleanName() . '.png';

        if ($request->hasFile('icon')) {
            $request
                ->file('icon')
                ->storeAs('operatorIcons', $filename, 'public');
        }

        if ($request->hasFile('portrait')) {
            $request
                ->file('portrait')
                ->storeAs('operatorPortraits', $filename, 'public');
        }

        return to_route('admin.dashboard')->with(
            'message',
            "Operator $operator->name successfully created !",
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOperationRequest;
use App\Models\Operation;
use Illuminate\Http\JsonResponse;

class OperationController extends Controller
{
    public function store(CreateOperationRequest $request): JsonResponse
    {
        $year = $request->input('year');
        $season = $request->input('season');
        $id = 'Y' . $year . 'S' . $season;

        if (Operation::find($id) !== null) {
            return response()->json(
                [
                    'errors' => [
                        'year' => [
                            'An operation already exists for that year and season.',
                        ],
                    ],
                ],
                422,
            );
        }

        $operation = Operation::create([
            'id' => $id,
            'name' => $request->input('name'),
            'year' => $year,
            'season' => $season,
            'release_date' => $request->input('release_date'),
        ]);

        return response()->json(
            [
                'id' => $operation->id,
                'name' => $operation->name,
                'release_date' => $operation->release_date,
            ],
            201,
        );
    }
}

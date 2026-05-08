<?php

use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

// Operator::factory() relies on Operation::factory(), which has a
// broken FK because Operation model is missing $incrementing = false.
// We bypass Eloquent inserts to work around that latent bug.

function insertOperation(string $id, int $year, int $season): void
{
    DB::table('operations')->insert([
        'id' => $id,
        'name' => 'Operation ' . $id,
        'year' => $year,
        'season' => $season,
        'release_date' => '2025-01-01',
    ]);
}

function insertOperator(array $data): Operator
{
    DB::table('operators')->insert(
        array_merge(
            [
                'id' => \Illuminate\Support\Str::ulid(),
                'name' => 'TestOp',
                'description' => 'A description.',
                'side' => 'Attack',
                'year' => 1,
                'season' => 1,
                'operation_id' => 'Y1S1_test',
            ],
            $data,
        ),
    );

    return Operator::where('name', $data['name'])->first();
}

it('casts year to integer after fetching from database', function () {
    insertOperation('Y4S1_test', 4, 1);

    $operator = insertOperator([
        'name' => 'CastTestA',
        'year' => 4,
        'season' => 1,
        'operation_id' => 'Y4S1_test',
    ]);

    expect($operator->year)->toBeInt();
});

it('casts season to integer after fetching from database', function () {
    insertOperation('Y3S2_test', 3, 2);

    $operator = insertOperator([
        'name' => 'CastTestB',
        'year' => 3,
        'season' => 2,
        'operation_id' => 'Y3S2_test',
    ]);

    expect($operator->season)->toBeInt();
});

it('year cast preserves the original value', function () {
    insertOperation('Y7S1_test', 7, 1);

    $operator = insertOperator([
        'name' => 'CastTestC',
        'year' => 7,
        'season' => 1,
        'operation_id' => 'Y7S1_test',
    ]);

    expect($operator->year)->toBe(7);
});

it('season cast preserves the original value', function () {
    insertOperation('Y2S3_test', 2, 3);

    $operator = insertOperator([
        'name' => 'CastTestD',
        'year' => 2,
        'season' => 3,
        'operation_id' => 'Y2S3_test',
    ]);

    expect($operator->season)->toBe(3);
});

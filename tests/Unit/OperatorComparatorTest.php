<?php

use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

// Operator::factory() / Operation::factory() have a broken FK chain
// because Operation model is missing $incrementing = false.
// Raw DB inserts bypass that latent bug so the comparator logic
// can still be covered.

// ──────────────────────────────────────────────────────────────────────────────
// compareReleaseDate
// ──────────────────────────────────────────────────────────────────────────────

it('returns negative when this operator has an earlier year', function () {
    $opA = makeOperator(year: 1, season: 1, side: 'Attack');
    $opB = makeOperator(year: 2, season: 1, side: 'Attack');

    $result = $opA->compareReleaseDate($opB);

    expect($result)->toBeLessThan(0);
});

it('returns positive when this operator has a later year', function () {
    $opA = makeOperator(year: 3, season: 1, side: 'Attack');
    $opB = makeOperator(year: 2, season: 1, side: 'Attack');

    $result = $opA->compareReleaseDate($opB);

    expect($result)->toBeGreaterThan(0);
});

it(
    'returns negative when year ties and this operator has earlier season',
    function () {
        $opA = makeOperator(year: 2, season: 1, side: 'Attack');
        $opB = makeOperator(year: 2, season: 3, side: 'Attack');

        $result = $opA->compareReleaseDate($opB);

        expect($result)->toBeLessThan(0);
    },
);

it(
    'returns positive when year ties and this operator has later season',
    function () {
        $opA = makeOperator(year: 2, season: 4, side: 'Attack');
        $opB = makeOperator(year: 2, season: 2, side: 'Attack');

        $result = $opA->compareReleaseDate($opB);

        expect($result)->toBeGreaterThan(0);
    },
);

it(
    'returns negative when year season tie and this is Attack vs Defense',
    function () {
        $opA = makeOperator(year: 2, season: 2, side: 'Attack');
        $opB = makeOperator(year: 2, season: 2, side: 'Defense');

        $result = $opA->compareReleaseDate($opB);

        expect($result)->toBeLessThan(0);
    },
);

it('returns zero for identical operators compared to themselves', function () {
    $op = makeOperator(year: 3, season: 1, side: 'Attack');

    $result = $op->compareReleaseDate($op);

    expect($result)->toBe(0);
});

it('reverses the result when reverse flag is true', function () {
    $opA = makeOperator(year: 1, season: 1, side: 'Attack');
    $opB = makeOperator(year: 2, season: 1, side: 'Attack');

    $forward = $opA->compareReleaseDate($opB, false);
    $reversed = $opA->compareReleaseDate($opB, true);

    expect($forward * $reversed)->toBeLessThan(0);
});

// ──────────────────────────────────────────────────────────────────────────────
// Helper
// ──────────────────────────────────────────────────────────────────────────────

function makeOperator(int $year, int $season, string $side): Operator
{
    static $seq = 0;
    $seq++;

    $opId = "Y{$year}S{$season}_t{$seq}";
    $opName = "OP{$seq}" . Str::random(4);

    DB::table('operations')->insert([
        'id' => $opId,
        'name' => 'Op ' . $opId,
        'year' => $year,
        'season' => $season,
        'release_date' => '2025-01-01',
    ]);

    $id = (string) Str::ulid();
    DB::table('operators')->insert([
        'id' => $id,
        'name' => $opName,
        'description' => 'Desc.',
        'side' => $side,
        'year' => $year,
        'season' => $season,
        'operation_id' => $opId,
    ]);

    return Operator::find($id);
}

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

// Launch cohort: every Y1S0 operator shares one operation but the
// operator-level year/season columns hold the in-game release order.
it(
    'sorts launch operators by their own year/season when sharing operation_id',
    function () {
        $doc = makeLaunchOperator(opYear: 0, opSeason: 5, side: 'Defense');
        $castle = makeLaunchOperator(opYear: 0, opSeason: 3, side: 'Defense');

        expect($doc->compareReleaseDate($castle))->toBeGreaterThan(0);
        expect($castle->compareReleaseDate($doc))->toBeLessThan(0);
    },
);

// A rework relocates the operator to its rework operation's slot,
// overriding both the operator's own year/season and the original
// operation.
it(
    'uses the rework operation year/season when a rework is attached',
    function () {
        $launchOp = makeLaunchOperator(opYear: 0, opSeason: 5, side: 'Defense');
        attachRework($launchOp, year: 9, season: 2);
        $launchOp = Operator::with('rework.operation')->find($launchOp->id);

        $modernOp = makeOperator(year: 9, season: 1, side: 'Defense');

        // launchOp's effective slot (Y9S2) > modernOp (Y9S1)
        expect($launchOp->compareReleaseDate($modernOp))->toBeGreaterThan(0);
    },
);

// Regression: ordinary operators (operator.year/season match
// operation.year/season) keep the same sort behaviour after the fix.
it(
    'keeps ordinary operator sort behaviour when columns match operation',
    function () {
        $opA = makeOperator(year: 4, season: 2, side: 'Attack');
        $opB = makeOperator(year: 4, season: 3, side: 'Attack');

        expect($opA->compareReleaseDate($opB))->toBeLessThan(0);
        expect($opB->compareReleaseDate($opA))->toBeGreaterThan(0);
    },
);

// ──────────────────────────────────────────────────────────────────────────────
// Helpers
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

// Builds an operator pointing at the shared 'Y1S0' launch operation
// while letting the caller decouple operator.year / operator.season.
function makeLaunchOperator(int $opYear, int $opSeason, string $side): Operator
{
    DB::table('operations')->updateOrInsert(
        ['id' => 'Y1S0'],
        [
            'name' => 'Game Launch',
            'year' => 1,
            'season' => 0,
            'release_date' => '2015-12-01',
        ],
    );

    static $seq = 0;
    $seq++;

    $id = (string) Str::ulid();
    DB::table('operators')->insert([
        'id' => $id,
        'name' => "Launch{$seq}" . Str::random(4),
        'description' => 'Desc.',
        'side' => $side,
        'year' => $opYear,
        'season' => $opSeason,
        'operation_id' => 'Y1S0',
    ]);

    return Operator::find($id);
}

function attachRework(Operator $operator, int $year, int $season): void
{
    $opId = "Y{$year}S{$season}_rework";

    DB::table('operations')->updateOrInsert(
        ['id' => $opId],
        [
            'name' => 'Rework ' . $opId,
            'year' => $year,
            'season' => $season,
            'release_date' => '2025-01-01',
        ],
    );

    DB::table('operator_rework')->insert([
        'operator_id' => $operator->id,
        'operation_id' => $opId,
    ]);
}

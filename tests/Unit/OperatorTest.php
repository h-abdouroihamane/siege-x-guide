<?php

use App\Models\Operation;
use App\Models\Operator;
use App\Models\Rework;

function makeOperator(
    int $year,
    int $season,
    string $side,
    string $name,
): Operator {
    $operation = Operation::factory()->create([
        'year' => $year,
        'season' => $season,
    ]);

    return Operator::factory()->create([
        'operation_id' => $operation->id,
        'side' => $side,
        'name' => $name,
    ]);
}

describe('getOperation', function () {
    it('returns the operator\'s own operation when not reworked', function () {
        $operation = Operation::factory()->create();
        $operator = Operator::factory()->create([
            'operation_id' => $operation->id,
        ]);

        expect($operator->getOperation()->is($operation))->toBeTrue();
    });

    it(
        'prefers the rework operation when the operator was reworked',
        function () {
            $original = Operation::factory()->create();
            $reworkOperation = Operation::factory()->create();
            $operator = Operator::factory()->create([
                'operation_id' => $original->id,
            ]);
            Rework::factory()->create([
                'operator_id' => $operator->id,
                'operation_id' => $reworkOperation->id,
            ]);

            expect($operator->getOperation()->is($reworkOperation))->toBeTrue();
        },
    );
});

describe('compareReleaseDate', function () {
    it('orders by operation year first', function () {
        $older = makeOperator(2, 1, 'Attack', 'Alpha');
        $newer = makeOperator(5, 1, 'Attack', 'Bravo');

        expect($older->compareReleaseDate($newer))->toBeLessThan(0);
        expect($newer->compareReleaseDate($older))->toBeGreaterThan(0);
    });

    it('falls back to season, then side, then name', function () {
        $earlierSeason = makeOperator(4, 1, 'Attack', 'Zulu');
        $laterSeason = makeOperator(4, 2, 'Defense', 'Alpha');
        expect($earlierSeason->compareReleaseDate($laterSeason))->toBeLessThan(
            0,
        );

        $attacker = makeOperator(4, 1, 'Attack', 'Zulu');
        $defender = makeOperator(4, 1, 'Defense', 'Alpha');
        expect($attacker->compareReleaseDate($defender))->toBeLessThan(0);

        $alpha = makeOperator(4, 1, 'Attack', 'Alpha');
        $bravo = makeOperator(4, 1, 'Attack', 'Bravo');
        expect($alpha->compareReleaseDate($bravo))->toBeLessThan(0);
    });

    it('honors the reverse flag', function () {
        $older = makeOperator(2, 1, 'Attack', 'Alpha');
        $newer = makeOperator(5, 1, 'Attack', 'Bravo');

        expect($older->compareReleaseDate($newer, true))->toBeGreaterThan(0);
    });
});

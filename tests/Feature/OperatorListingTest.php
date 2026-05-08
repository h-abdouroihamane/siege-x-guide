<?php

use App\Models\Operation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Operator::factory() is broken due to the Operation model missing
// $incrementing = false (FK integrity violation). Raw inserts are used
// where an operator record is needed.

// ──────────────────────────────────────────────────────────────────────────────
// Operators index
// ──────────────────────────────────────────────────────────────────────────────

it('guest can reach the operators index page', function () {
    $response = $this->get('/operators');

    $response->assertStatus(200);
});

it(
    'operators index page returns an Inertia OperatorsView component',
    function () {
        $response = $this->get('/operators');

        $response->assertInertia(
            fn($page) => $page->component('OperatorsView'),
        );
    },
);

it('operators index Inertia props contain an operators key', function () {
    insertListingOperator('Sledge');

    $response = $this->get('/operators');

    $response->assertInertia(fn($page) => $page->has('operators'));
});

it('operators index exposes the seeded operator in props', function () {
    insertListingOperator('Thatcher');

    $response = $this->get('/operators');

    $response->assertInertia(
        fn($page) => $page
            ->has('operators.data', 1)
            ->has(
                'operators.data.0',
                fn($op) => $op->where('name', 'Thatcher')->etc(),
            ),
    );
});

// ──────────────────────────────────────────────────────────────────────────────
// Vocabulary page
// ──────────────────────────────────────────────────────────────────────────────

it('guest can reach the vocabulary page', function () {
    $response = $this->get('/vocabulary');

    $response->assertStatus(200);
});

it('vocabulary page returns the Vocabulary Inertia component', function () {
    $response = $this->get('/vocabulary');

    $response->assertInertia(fn($page) => $page->component('Vocabulary'));
});

// ──────────────────────────────────────────────────────────────────────────────
// Squads page — requires at least one operation (mostRecent scope)
// ──────────────────────────────────────────────────────────────────────────────

it('guest can reach the squads page when an operation exists', function () {
    Operation::factory()->create();

    $response = $this->get('/squads');

    $response->assertStatus(200);
});

it('squads page returns the SquadsView Inertia component', function () {
    Operation::factory()->create();

    $response = $this->get('/squads');

    $response->assertInertia(fn($page) => $page->component('SquadsView'));
});

it('squads Inertia props contain operationName', function () {
    Operation::factory()->create(['name' => 'Operation New Blood']);

    $response = $this->get('/squads');

    $response->assertInertia(fn($page) => $page->has('operationName'));
});

// ──────────────────────────────────────────────────────────────────────────────
// Secondary gadgets page — requires at least one operation
// ──────────────────────────────────────────────────────────────────────────────

it(
    'guest can reach the secondary-gadgets page when an operation exists',
    function () {
        Operation::factory()->create();

        $response = $this->get('/secondary-gadgets');

        $response->assertStatus(200);
    },
);

it('secondary-gadgets page returns SecondaryGadgetView component', function () {
    Operation::factory()->create();

    $response = $this->get('/secondary-gadgets');

    $response->assertInertia(
        fn($page) => $page->component('SecondaryGadgetView'),
    );
});

// ──────────────────────────────────────────────────────────────────────────────
// Helper
// ──────────────────────────────────────────────────────────────────────────────

/**
 * Insert an operation + operator directly to bypass the
 * OperatorFactory FK bug (Operation model missing $incrementing=false).
 */
function insertListingOperator(string $name): void
{
    static $seq = 0;
    $seq++;

    $opId = "Y1S1_list{$seq}";

    DB::table('operations')->insert([
        'id' => $opId,
        'name' => 'Operation List ' . $seq,
        'year' => 1,
        'season' => 1,
        'release_date' => '2025-01-01',
    ]);

    DB::table('operators')->insert([
        'id' => (string) Str::ulid(),
        'name' => $name,
        'description' => 'Listed.',
        'side' => 'Attack',
        'year' => 1,
        'season' => 1,
        'operation_id' => $opId,
    ]);
}

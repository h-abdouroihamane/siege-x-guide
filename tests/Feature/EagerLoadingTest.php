<?php

use App\Models\Operation;
use App\Models\Operator;
use App\Models\Rework;
use App\Models\SecondaryGadget;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\get;

it('serves /operators/all with a constant query count (no N+1)', function () {
    $reworked = Operator::factory()->create();
    Rework::factory()->create([
        'operator_id' => $reworked->id,
        'operation_id' => Operation::factory()->create()->id,
    ]);
    Operator::factory()->create();

    DB::enableQueryLog();
    get('/operators/all')->assertOk();
    $baseline = count(DB::getQueryLog());
    DB::flushQueryLog();

    Operator::factory()->count(20)->create();
    DB::flushQueryLog();
    get('/operators/all')->assertOk();
    $scaled = count(DB::getQueryLog());
    DB::disableQueryLog();

    expect($scaled)->toBe($baseline);
});

it(
    'serves /secondary-gadgets with a constant query count (no N+1)',
    function () {
        Operation::factory()->create();

        $makeGadget = function () {
            SecondaryGadget::factory()
                ->create(['side' => 'Attack'])
                ->operators()
                ->attach(
                    Operator::factory()
                        ->count(3)
                        ->create(['side' => 'Attack'])
                        ->pluck('id'),
                );
        };

        $makeGadget();
        DB::flushQueryLog();
        DB::enableQueryLog();
        get('/secondary-gadgets')->assertOk();
        $baseline = DB::getQueryLog();
        DB::disableQueryLog();

        foreach (range(1, 8) as $ignored) {
            $makeGadget();
        }
        DB::flushQueryLog();
        DB::enableQueryLog();
        get('/secondary-gadgets')->assertOk();
        $scaled = DB::getQueryLog();
        DB::disableQueryLog();

        expect(count($scaled))->toBe(count($baseline));
    },
);

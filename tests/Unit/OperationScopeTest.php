<?php

use App\Models\Operation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns the operation with the highest year first', function () {
    Operation::factory()->create(['year' => 3, 'season' => 1]);
    Operation::factory()->create(['year' => 5, 'season' => 1]);
    Operation::factory()->create(['year' => 1, 'season' => 4]);

    $result = Operation::mostRecent()->first();

    expect($result->year)->toBe(5);
});

it('uses season as a tiebreak when year values are equal', function () {
    Operation::factory()->create(['year' => 4, 'season' => 1]);
    Operation::factory()->create(['year' => 4, 'season' => 3]);
    Operation::factory()->create(['year' => 4, 'season' => 2]);

    $result = Operation::mostRecent()->first();

    expect($result->season)->toBe(3);
});

it('returns the highest year regardless of insertion order', function () {
    Operation::factory()->create(['year' => 10, 'season' => 4]);
    Operation::factory()->create(['year' => 2, 'season' => 2]);

    $result = Operation::mostRecent()->first();

    expect($result->year)->toBe(10);
});

it('returns null without throwing when there are no operations', function () {
    $result = Operation::mostRecent()->first();

    expect($result)->toBeNull();
});

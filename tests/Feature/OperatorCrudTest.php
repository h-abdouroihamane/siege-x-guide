<?php

use App\Models\Operation;
use App\Models\Operator;
use App\Models\Squad;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// ──────────────────────────────────────────────────────────────────────────────
// Auth / middleware enforcement
// ──────────────────────────────────────────────────────────────────────────────

// FAILING due to latent bug: bootstrap/app.php does not configure the auth
// middleware's redirectTo to use 'admin.login'. Laravel falls back to
// route('login') which is not defined and throws RouteNotFoundException (500).
it('redirects a guest away from the operator store endpoint', function () {
    $response = $this->post('/operators/store', []);

    $response->assertRedirect(route('admin.login'));
});

// ──────────────────────────────────────────────────────────────────────────────
// CreateOperatorRequest — required field rules
// ──────────────────────────────────────────────────────────────────────────────

it('rejects store when operationName is missing', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    unset($payload['operationName']);

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('operationName');
});

it('rejects store when year is missing', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    unset($payload['year']);

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('year');
});

it('rejects store when season is missing', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    unset($payload['season']);

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('season');
});

it('rejects store when season is not in 1 2 3 4', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    $payload['season'] = 5;

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('season');
});

it('rejects store when releaseDate is missing', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    unset($payload['releaseDate']);

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('releaseDate');
});

it('rejects store when operator name is missing', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    unset($payload['name']);

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('name');
});

it('rejects store when description is missing', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    unset($payload['description']);

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('description');
});

it('rejects store when side is missing', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    unset($payload['side']);

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('side');
});

it('rejects store when side is not Attack or Defense', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    $payload['side'] = 'Support';

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('side');
});

it('rejects store when squad does not exist in database', function () {
    $admin = User::factory()->create();

    $payload = validStorePayload('NonExistentSquad');

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('squad');
});

it('rejects store when operationName is not unique', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    DB::table('operations')->insert([
        'id' => 'Y1S1_dup',
        'name' => 'Operation Duplicate',
        'year' => 1,
        'season' => 1,
        'release_date' => '2025-01-01',
    ]);

    $payload = validStorePayload($squad->name);
    $payload['operationName'] = 'Operation Duplicate';

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('operationName');
});

it('rejects store when operator name is not unique', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    seedOperator('Lion');

    $payload = validStorePayload($squad->name);
    $payload['name'] = 'Lion';

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('name');
});

// ──────────────────────────────────────────────────────────────────────────────
// Admin can create an operator with valid data
// ──────────────────────────────────────────────────────────────────────────────

it('stores an operator and redirects admin to dashboard', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    $payload['icon'] = UploadedFile::fake()->image('icon.png', 250, 250);
    $payload['portrait'] = UploadedFile::fake()->image(
        'portrait.png',
        300,
        500,
    );

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertRedirect(route('admin.dashboard'));
});

it('persists the operator row in the database after store', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    $payload['icon'] = UploadedFile::fake()->image('icon.png', 250, 250);
    $payload['portrait'] = UploadedFile::fake()->image(
        'portrait.png',
        300,
        500,
    );

    $this->actingAs($admin)->post('/operators/store', $payload);

    $this->assertDatabaseHas('operators', ['name' => $payload['name']]);
});

// ──────────────────────────────────────────────────────────────────────────────
// EditOperatorRequest — update path
// ──────────────────────────────────────────────────────────────────────────────

it('rejects update when operator name is missing', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();
    [$opId, $operatorId] = seedOperatorWithSquad($squad);

    $payload = validUpdatePayload($opId, $squad->name);
    unset($payload['name']);

    $response = $this->actingAs($admin)->post(
        "/operators/update/{$operatorId}",
        $payload,
    );

    $response->assertSessionHasErrors('name');
});

it('rejects update when side is invalid', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();
    [$opId, $operatorId] = seedOperatorWithSquad($squad);

    $payload = validUpdatePayload($opId, $squad->name);
    $payload['side'] = 'Neutral';

    $response = $this->actingAs($admin)->post(
        "/operators/update/{$operatorId}",
        $payload,
    );

    $response->assertSessionHasErrors('side');
});

it('rejects update when squad does not exist', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();
    [$opId, $operatorId] = seedOperatorWithSquad($squad);

    $payload = validUpdatePayload($opId, 'GhostSquad');

    $response = $this->actingAs($admin)->post(
        "/operators/update/{$operatorId}",
        $payload,
    );

    $response->assertSessionHasErrors('squad');
});

it('rejects update when operation_id does not exist', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();
    [$opId, $operatorId] = seedOperatorWithSquad($squad);

    $payload = validUpdatePayload('nonexistent-id', $squad->name);

    $response = $this->actingAs($admin)->post(
        "/operators/update/{$operatorId}",
        $payload,
    );

    $response->assertSessionHasErrors('operation_id');
});

it('updates operator and redirects admin to dashboard', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();
    [$opId, $operatorId] = seedOperatorWithSquad($squad);

    $payload = validUpdatePayload($opId, $squad->name);
    $payload['name'] = 'UpdatedName';

    $response = $this->actingAs($admin)->post(
        "/operators/update/{$operatorId}",
        $payload,
    );

    $response->assertRedirect(route('admin.dashboard'));
});

it('persists the updated operator name in the database', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();
    [$opId, $operatorId] = seedOperatorWithSquad($squad);

    $payload = validUpdatePayload($opId, $squad->name);
    $payload['name'] = 'UpdatedName';

    $this->actingAs($admin)->post("/operators/update/{$operatorId}", $payload);

    $this->assertDatabaseHas('operators', ['name' => 'UpdatedName']);
});

// ──────────────────────────────────────────────────────────────────────────────
// description max:250 boundary — store
// ──────────────────────────────────────────────────────────────────────────────

it('rejects store when description exceeds 250 characters', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    $payload['description'] = str_repeat('a', 251);

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('description');
});

it('accepts store when description is exactly 250 characters', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    $payload['description'] = str_repeat('a', 250);
    $payload['icon'] = UploadedFile::fake()->image('icon.png', 250, 250);
    $payload['portrait'] = UploadedFile::fake()->image(
        'portrait.png',
        300,
        500,
    );

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertRedirect(route('admin.dashboard'));
});

// ──────────────────────────────────────────────────────────────────────────────
// description max:250 boundary — update
// ──────────────────────────────────────────────────────────────────────────────

it('rejects update when description exceeds 250 characters', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();
    [$opId, $operatorId] = seedOperatorWithSquad($squad);

    $payload = validUpdatePayload($opId, $squad->name);
    $payload['description'] = str_repeat('a', 251);

    $response = $this->actingAs($admin)->post(
        "/operators/update/{$operatorId}",
        $payload,
    );

    $response->assertSessionHasErrors('description');
});

it('accepts update when description is exactly 250 characters', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();
    [$opId, $operatorId] = seedOperatorWithSquad($squad);

    $payload = validUpdatePayload($opId, $squad->name);
    $payload['description'] = str_repeat('a', 250);

    $response = $this->actingAs($admin)->post(
        "/operators/update/{$operatorId}",
        $payload,
    );

    $response->assertRedirect(route('admin.dashboard'));
});

// ──────────────────────────────────────────────────────────────────────────────
// OperationController — store endpoint
// ──────────────────────────────────────────────────────────────────────────────

it('rejects operation store from a guest', function () {
    $payload = [
        'name' => 'Operation Guest',
        'year' => 9,
        'season' => 2,
        'release_date' => '2026-01-01',
    ];

    $response = $this->post(route('operation.store'), $payload);

    $response->assertRedirect();
});

it('rejects operation store when name is missing', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)->postJson(route('operation.store'), [
        'year' => 9,
        'season' => 2,
        'release_date' => '2026-01-01',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('name');
});

it('stores an operation and returns 201 json with id', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)->postJson(route('operation.store'), [
        'name' => 'Operation Test',
        'year' => 9,
        'season' => 2,
        'release_date' => '2026-01-01',
    ]);

    $response->assertCreated();
    $response->assertJsonStructure(['id', 'name', 'release_date']);
    expect($response->json('id'))->toBe('Y9S2');
});

it('rejects operation store when year/season slot already exists', function () {
    $admin = User::factory()->create();

    Operation::create([
        'id' => 'Y9S2',
        'name' => 'Operation Existing',
        'year' => 9,
        'season' => 2,
        'release_date' => '2026-01-01',
    ]);

    $response = $this->actingAs($admin)->postJson(route('operation.store'), [
        'name' => 'Operation Different Name',
        'year' => 9,
        'season' => 2,
        'release_date' => '2026-01-01',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('year');
});

// ──────────────────────────────────────────────────────────────────────────────
// Operator gadget sync — secondary_gadget_ids
// ──────────────────────────────────────────────────────────────────────────────

it('accepts store with valid secondary_gadget_ids', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $squad = Squad::factory()->create();
    $gadgetId = seedSecondaryGadget('Attack');

    $payload = validStorePayload($squad->name);
    $payload['secondary_gadget_ids'] = [$gadgetId];
    $payload['icon'] = UploadedFile::fake()->image('icon.png', 250, 250);
    $payload['portrait'] = UploadedFile::fake()->image(
        'portrait.png',
        300,
        500,
    );

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertDatabaseHas('operator_secondary_gadget', [
        'secondary_gadget_id' => $gadgetId,
    ]);
});

it('rejects store with nonexistent secondary_gadget_id', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();

    $payload = validStorePayload($squad->name);
    $payload['secondary_gadget_ids'] = ['01HFAKE0000000000000000000'];

    $response = $this->actingAs($admin)->post('/operators/store', $payload);

    $response->assertSessionHasErrors('secondary_gadget_ids.0');
});

it('syncs secondary gadgets on update', function () {
    $admin = User::factory()->create();
    $squad = Squad::factory()->create();
    [$opId, $operatorId] = seedOperatorWithSquad($squad);
    $gadgetId = seedSecondaryGadget('Defense');

    $payload = validUpdatePayload($opId, $squad->name);
    $payload['secondary_gadget_ids'] = [$gadgetId];

    $this->actingAs($admin)->post("/operators/update/{$operatorId}", $payload);

    $this->assertDatabaseHas('operator_secondary_gadget', [
        'secondary_gadget_id' => $gadgetId,
    ]);
});

// ──────────────────────────────────────────────────────────────────────────────
// Helpers
// ──────────────────────────────────────────────────────────────────────────────

function validStorePayload(string $squadName): array
{
    return [
        'operationName' => 'Operation TestOp',
        'year' => 9,
        'season' => 2,
        'releaseDate' => '2025-01-01',
        'name' => 'TestOperator',
        'description' => 'A test operator.',
        'side' => 'Attack',
        'squad' => $squadName,
    ];
}

function validUpdatePayload(string $operationId, string $squadName): array
{
    return [
        'name' => 'UpdatedOperator',
        'description' => 'Updated description.',
        'side' => 'Defense',
        'operation_id' => $operationId,
        'squad' => $squadName,
    ];
}

/**
 * Insert an operator row directly, bypassing the OperatorFactory
 * FK bug (Operation model missing $incrementing = false).
 */
function seedOperator(string $name): string
{
    static $seq = 0;
    $seq++;

    $opId = "Y1S1_seed{$seq}";

    DB::table('operations')->insertOrIgnore([
        'id' => $opId,
        'name' => 'Operation Seed ' . $seq,
        'year' => 1,
        'season' => 1,
        'release_date' => '2025-01-01',
    ]);

    $id = (string) Str::ulid();
    DB::table('operators')->insert([
        'id' => $id,
        'name' => $name,
        'description' => 'Seeded.',
        'side' => 'Attack',
        'year' => 1,
        'season' => 1,
        'operation_id' => $opId,
    ]);

    return $id;
}

/**
 * Seed an operator + attach it to a squad, return [operationId, operatorId].
 */
function seedOperatorWithSquad(Squad $squad): array
{
    static $seq = 0;
    $seq++;

    $opId = "Y2S1_edit{$seq}";

    DB::table('operations')->insert([
        'id' => $opId,
        'name' => 'Operation Edit ' . $seq,
        'year' => 2,
        'season' => 1,
        'release_date' => '2025-01-01',
    ]);

    $operatorId = (string) Str::ulid();
    DB::table('operators')->insert([
        'id' => $operatorId,
        'name' => 'EditOp' . $seq,
        'description' => 'Editable.',
        'side' => 'Attack',
        'year' => 2,
        'season' => 1,
        'operation_id' => $opId,
    ]);

    DB::table('operator_squad')->insert([
        'operator_id' => $operatorId,
        'squad_id' => $squad->id,
        'rank' => 1,
    ]);

    return [$opId, $operatorId];
}

/**
 * Insert a secondary_gadgets row directly, return its ULID.
 */
function seedSecondaryGadget(string $side): string
{
    $id = (string) Str::ulid();

    DB::table('secondary_gadgets')->insert([
        'id' => $id,
        'name' => 'Gadget ' . $id,
        'side' => $side,
    ]);

    return $id;
}

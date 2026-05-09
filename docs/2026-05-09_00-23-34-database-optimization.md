# HANDOFF: database optimization wave

State of the database refactor as of branch
`claude/optimize-database-QAsK8`. Last commit: `3d18788`.

This file picks up after the database-engineer audit run on
2026-05-09 against `main` at `3cc5ec6` (post PR #9). Read it
before continuing the wave.

---

## What this wave is about

A read-only audit by `database-engineer` on the operators / pivot
tables turned up 11 issues across HIGH / MEDIUM / LOW. We fixed
the top three and discovered (mid-wave) that one of the
"redundant column" findings was actually wrong — those columns
encode launch-cohort information. That triggered a small
follow-up fix to wire them into the sort.

---

## What's done

Three commits on this branch, all pushed:

### `9fb0985` — perf(db): kill N+1 in operator accessors and harden pivot schema

- **Operator model accessors stop re-querying.**
  `Operator::getRoles`, `getSquad`, `getOperation` now read the
  already-loaded relations instead of calling `()->get()` /
  `()->first()` on the relation builders. Previously every call
  fired a fresh DB query even when the controller had eager-
  loaded the data. Affected files:
    - `app/Models/Operator.php` lines ~34–95
- **Eager-load `rework.operation` on the consumers** so
  `getOperation()` doesn't lazy-load. Affected files:
    - `app/Http/Controllers/OperatorController.php` (`getAll`,
      `selectPost`)
    - `app/Http/Controllers/SecondaryGadgetController.php`
      (`getGadgets`)
- **Pivot FKs gained `onDelete`.** New migration
  `database/migrations/2026_05_08_120000_add_on_delete_to_pivot_foreign_keys.php`
  drops and re-adds every FK on `operator_role`, `operator_squad`,
  `operator_queer_identity`, `operator_secondary_gadget`,
  `operator_rework`. Pattern: cascade from `operators`; restrict
  on reference data (roles, squads, gadgets, operations); cascade
  for `queer_identities` (pure tag data). Closes the AGENTS.md §7
  requirement.
- **Composite primary keys on every pivot table.** New migration
  `database/migrations/2026_05_08_120100_add_composite_primary_keys_to_pivot_tables.php`
  adds `primary(['operator_id', '<other>_id'])` to all five
  pivots. Duplicate rows are now blocked at the DB level instead
  of relying on the application.

### `67f1553` — fix(sort): drive operator release-date order from operator.year/season

Came out of a user clarification mid-wave. The `operators.year`
and `operators.season` columns are **not** redundant: the 20
launch operators all share `operation_id='Y1S0'` but each carries
distinct operator-level year/season values which encode their
in-game release order.

- **`Operator::sortableYearSeason()`** returns
  `[rework.operation.year, rework.operation.season]` if a rework
  exists, else `[$this->year, $this->season]`.
- **`Operator::compareReleaseDate()`** uses the helper instead of
  `getOperation()->year`.
- **`OperatorResource::toArray()`** sends the same helper output
  to the frontend, so `Operator.compareRelease` (which already
  reads `this.year` / `this.season`) auto-aligns.
- **Three new Pest tests** in
  `tests/Unit/OperatorComparatorTest.php`: launch-cohort sort,
  rework override, ordinary-operator regression. Two new helpers:
  `makeLaunchOperator` and `attachRework`.

### `3d18788` — fix(seeder): align Y1S0 operator seasons with Ubisoft's official release order

User supplied Ubisoft's published release order for the launch
cohort. Reassigned `operators.season` 1..20 oldest → most recent:
Kapkan=1, Tachanka=2, Glaz=3, Fuze=4, IQ=5, Blitz=6, Bandit=7,
Jäger=8, Rook=9, Doc=10, Twitch=11, Montagne=12, Thermite=13,
Pulse=14, Castle=15, Ash=16, Thatcher=17, Smoke=18, Sledge=19,
Mute=20.

Verified end-to-end: `Operator::with('rework.operation')->where('operation_id', 'Y1S0')->get()->sort(reverse)->pluck('name')`
produces the Ubisoft list exactly.

### Concrete outcomes

- 59 Pest tests green (was 56 — added 3 comparator cases).
- N+1 eliminated on `/operators` and `/secondary-gadgets`.
- Pivot tables protected against orphaned rows and duplicates at
  the DB level.
- Launch-cohort sort works in-game-correctly on both backend and
  frontend.

---

## What's left

Punch list straight from the audit, minus what we did and minus
what we explicitly rejected. Pick any order — items are
independent.

### HIGH

#### 1. `config/database.php` defaults to `sqlite`

- **File**: `config/database.php` line 17
- **Symptom**: `'default' => env('DB_CONNECTION', 'sqlite')`. The
  deployed DB is MySQL 8 (per AGENTS.md §1, hosted on OVH). Any
  fresh deploy or CI run with an incomplete `.env` silently falls
  back to SQLite, hiding MySQL-specific issues (strict mode, index
  length limits, utf8mb4 collation).
- **Fix**: change the fallback string from `'sqlite'` to
  `'mysql'`.
- **Caveat**: `phpunit.xml` and `.env.example` may rely on the
  sqlite default. Check both before flipping. If tests use a
  separate `DB_CONNECTION=sqlite` line in `phpunit.xml` (they
  currently do — see `phpunit.xml:30`), no test impact.
- **Risk**: low. One-line change. Tested via `php artisan test`
  and a fresh `migrate:fresh --env=local`.
- **Owner**: `database-engineer`.

### MEDIUM

#### 2. `Operation::operators()` is a mis-declared `belongsToMany`

- **File**: `app/Models/Operation.php`
- **Symptom**: declared `belongsToMany(Operator::class,
'operators')` — that's pointing at the operators table itself
  as if it were a pivot. The real shape is `hasMany`: each
  operator has one `operation_id` FK directly on `operators`.
- **Fix**: replace with
  `hasMany(Operator::class, 'operation_id')`.
- **Caveat**: search for callers (`grep -rn
'->operators' app/ resources/`). If anything calls
  `$operation->operators()->attach(...)` or relies on pivot
  semantics, that's a separate bug to surface. Likely none.
- **Risk**: low.
- **Owner**: `database-engineer`.

#### 3. Seeders fire per-row `firstWhere('name', …)` against an un-indexed column

- **Files**:
    - `database/seeders/OperatorReworkSeeder.php` — one
      `Operator::firstWhere('name', $rop['name'])` per rework row
    - `database/seeders/SquadSeeder.php` — same pattern per
      operator-in-squad (~80 lookups)
- **Symptom**: `operators.name` has no index (see migration
  `2025_05_14_142159_create_operators_table.php`); each lookup is
  a full table scan. Same column is also queried by
  `OperatorController::selectPost`.
- **Fix**: two parts.
    1. New migration adding `$table->index('name')` (or
       `$table->unique('name')` if names must be unique — confirm
       with the user, since `$fillable`-style constraints aren't
       enforced) on `operators.name`.
    2. Refactor both seeders to pre-load operators into a
       `keyBy('name')` collection before the loop, then index into
       it. Same for `Squad::firstWhere('name', ...)` in
       `SquadSeeder` if it shows up.
- **Risk**: low. Seeders only run during `db:seed`.
- **Owner**: `database-engineer`.

#### 4. `Rework` model imports `HasUlids` but doesn't use it

- **File**: `app/Models/Rework.php`
- **Symptom**: `use Illuminate\Database\Eloquent\Concerns\HasUlids;`
  at the top, never applied via `use HasUlids` in the class body.
  More to the point: `operator_rework` has no `id` column — both
  columns form a natural composite PK (added by our wave 1
  migration above). If `HasUlids` were active it would fail
  trying to assign a ULID to a non-existent column.
- **Fix**: drop the unused import. That's it.
- **Risk**: trivial.
- **Owner**: `database-engineer`.

### LOW

#### 5. `secondary_gadgets.name` missing unique constraint

- **File**: `database/migrations/2025_09_06_112613_create_secondary_gadgets_table.php`
- **Inconsistency**: `roles.name` and `squads.name` both have
  `->unique()`. `secondary_gadgets.name` does not, so duplicate
  gadget names ("Impact Grenade") can be inserted.
- **Fix**: new forward-only migration
  `add_unique_to_secondary_gadgets_name` adding
  `$table->unique('name')`.
- **Risk**: low — but check for existing duplicates in the
  current dataset before applying in production.
- **Owner**: `database-engineer`.

#### 6. `queer_identities.name` missing unique constraint

- **File**: `database/migrations/2025_06_18_125530_create_queer_identities_table.php`
- **Same inconsistency** as #5. New migration adding
  `$table->unique('name')`.
- **Risk**: low.
- **Owner**: `database-engineer`.

#### 7. `SquadFactory` uses a process-static rank counter

- **File**: `database/factories/SquadFactory.php`
- **Symptom**: `static $rank = 0` lives at the process level, so
  if two tests in the same Pest run create squads, the second
  test's ranks don't start from 1. Any test that asserts a
  specific rank value will flake on second run.
- **Fix**: replace with
  `Squad::max('rank') + 1` inside `definition()`, or use Laravel's
  factory sequence:
  `$this->sequence(fn ($seq) => ['rank' => $seq->index + 1])`.
- **Risk**: low.
- **Owner**: `database-engineer`.

---

## What's been explicitly rejected

The audit's **Medium #5** ("`operators.year` and `operators.season`
are redundant — drop them") is **wrong** and we should **not**
do it. The columns encode the launch cohort's in-game release
order: all 20 Y1S0 operators share `operation_id='Y1S0'` but
have distinct operator-level year/season values driving sort.
See `67f1553` and `3d18788` above. Don't re-raise this finding.

If a future agent re-runs the audit and flags it again, point at
this handoff or at `Operator::sortableYearSeason()` for the
explanation.

---

## Open architecture questions

A few things came up during the wave that the user hasn't decided
on:

- **Should `compareRelease` on the frontend (`resources/js/scripts/operator.ts`)
  also be tested?** The Pest suite covers backend
  `compareReleaseDate`. There is no equivalent frontend unit
  test. The two algorithms must stay in sync; a divergence would
  silently break the UI. A small Vitest spec on the `Operator`
  class would close the gap.
- **MySQL strict-mode parity in tests**: phpunit.xml uses
  `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:`. SQLite is
  permissive about FK behavior and column types. Our migration
  for composite PKs and onDelete worked under SQLite, but real
  validation will only happen against MySQL 8 in CI or
  production. Worth running `migrate:fresh` against a local
  MySQL once before the next merge.
- **`operators.name` index**: open whether to make it `unique()`
  or just an `index()`. The audit's recommendation is index
  only; the codebase treats names as identifiers (`selectPost`
  looks operators up by name). Probably want `unique()`. Ask the
  user.

---

## How to continue

```bash
git fetch origin
git checkout claude/optimize-database-QAsK8
git pull
```

Branch is at `3d18788`, ahead of `main` by 3 commits. Tests:
59 passed, 154 assertions. Nothing in flight on disk
(`git status` clean).

Suggested next batch: HIGH #1 (config default) + MEDIUM #2
(Operation::operators) + MEDIUM #4 (drop dead HasUlids import).
All three are one-line / one-file changes and ship together
cleanly.

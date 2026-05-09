# HANDOFF: operators.json as seeder source of truth

State of the work as of branch
`claude/hide-admin-panel-production-sB3Hm`. Last commit: `46a5bcf`.

This file picks up after the admin-panel session that started as
"hide /admin/login in production" and pivoted into a request to make
admin edits flow back into the seeders. The hide-the-panel work was
**not** done in this branch despite the name — only the JSON-source-
of-truth refactor.

---

## What this wave is about

The admin in production needs to add and edit operators from the
panel without falling out of sync with the checked-in seeders. The
old `OperatorSeeder` carried an inline PHP array of 77 operators,
and the relation seeders (`Squad`, `Role`, `QueerIdentity`,
`SecondaryGadget`) carried their own per-operator attachment data.
Any production edit was invisible to git.

The fix: make `database/data/operators.json` the canonical store
for everything the admin panel can edit, have the seeder read from
it, and have the controller write back to it on save.

---

## What's done

Two commits on this branch, both pushed:

### `7df3957` — Make operators.json the source of truth for seeded operator data

- **`database/data/operators.json`** (new): 77 operators with attrs
  (name, description, side, year, season, operation_id) plus
  `squad: {name, rank}`, `roles[]`, `queer_identities[]`, and
  `secondary_gadgets[]`. Sorted by operator name. Pretty-printed
  with `JSON_UNESCAPED_UNICODE` so diacritics (Capitão, Jäger, Nøkk,
  Tubarão) survive review.
- **`OperatorSeeder`** (`database/seeders/OperatorSeeder.php`):
  reads the JSON, creates each operator, attaches squad with the
  pivot rank, attaches roles / queer identities / secondary gadgets
  by name. Uses `WithoutModelEvents` so the observer doesn't fire
  77× during seeding.
- **Lookup seeders** (`SquadSeeder`, `RoleSeeder`,
  `QueerIdentitySeeder`, `SecondaryGadgetSeeder`): stripped of all
  per-operator attachment logic. They now only seed their own
  table. `SecondaryGadgetSeeder` got each gadget's `side`
  hard-coded (was previously inferred from the first operator).
- **`DatabaseSeeder`**: reordered to `User → Operation → Role →
  Squad → QueerIdentity → SecondaryGadget → Operator →
  OperatorRework`. Lookup tables must exist before
  `OperatorSeeder` resolves names.
- **`App\Support\OperatorJsonWriter`** (new): static `write()`
  dumps the operator graph (with eager-loaded relations) back to
  JSON. No-ops in tests via `app()->runningUnitTests()`.
- **`App\Observers\OperatorObserver`** (new): `saved()` and
  `deleted()` both call the writer. Wired via
  `#[ObservedBy([OperatorObserver::class])]` on the `Operator`
  model.
- **`OperatorController::store/update`**: append a single
  `OperatorJsonWriter::write()` call after pivot syncs, so the
  JSON captures relations correctly. The observer is a safety net
  for non-controller paths (tinker, future console commands, etc.)
  but the explicit call is the deterministic primary trigger,
  because pivot `attach`/`sync` calls do not fire the parent
  model's `saved` event.

Verification at this commit:
- `migrate:fresh --seed` followed by re-running
  `OperatorJsonWriter::write()` produces a JSON file byte-identical
  to the pre-refactor dump (`diff -q` clean).
- All 73 Pest tests still pass (194 assertions).

### `46a5bcf` — Document operators.json as the seeder source of truth

`AGENTS.md` updated in two spots:
- **§3 file tree**: surfaces `database/data/`, `app/Observers/`,
  and `app/Support/`.
- **§7 Database & migrations**: new "Operators: JSON-as-source-of-
  truth" subsection codifying the read-from-JSON / write-on-save
  rule, the no-attach lookup-seeder rule, and the prod → local
  pull-and-commit workflow.

`README.md` got a parallel "Seeded operator data" paragraph in the
"Database schema invariants" section. Forward-looking docs only —
the wave-specific narrative is this file.

---

### Concrete outcomes

- 73 Pest tests green (no change in count).
- Admin panel edits in production now produce a diffable file in
  the repo. No DB dump or seeder re-write needed.
- Lookup seeders shrank substantially (SquadSeeder went from ~100
  lines to ~30; SecondaryGadgetSeeder from ~200 to ~40).
- One pre-existing data bug surfaced: `QueerIdentitySeeder` had a
  case mismatch (`'Non-Binary'` lookup vs. `'Non-binary'` canonical)
  so Sens shipped with no queer identity attached. The JSON dump
  preserves the current (broken) state to keep `migrate:fresh
  --seed` byte-identical to the previous behavior. Fix it via the
  admin panel post-deploy and the JSON will pick up the correction
  automatically.

---

## What's left

### The original ask — hide the admin panel in production

The branch is named `claude/hide-admin-panel-production-sB3Hm`
because that's what the user originally wanted. We discussed five
options (URL obscurity, env-gated routes, IP allowlist, signed URL,
basic auth) and the user picked **#1: secret path via env var**
("hide invisibly from crawlers, accessible from anywhere with a
bookmarked URL"). That work is **not done**. To pick it up:

- Read the existing `routes/admin.php` — currently `/admin/login`
  GET + POST are unconditionally registered.
- Add `ADMIN_PATH` to `.env.example` (e.g. `ADMIN_PATH=admin`) and
  wire `routes/admin.php` to use `Route::prefix(config(...))`
  instead of the literal `'admin'`. Default to `'admin'` so dev
  doesn't break when the env var is missing.
- Update `route('admin.login')` callers (e.g. `AdminController`,
  any redirects) — Ziggy will pick up the new path automatically
  from the route name.
- Update `AGENTS.md §1` ("Login at `/admin/login`") and §5 to
  reflect that the path is configurable.
- Production gets a long random `ADMIN_PATH` value in the OVH
  env; the literal `/admin/login` returns 404.

Risk: low. One env var, one config call, one route file. No DB
work.

### Pre-existing data bug — Sens has no queer identity

- **File**: `database/data/operators.json`, "Sens" entry.
- **Symptom**: `queer_identities: []`. Sens is non-binary.
- **Root cause** (now historical): `QueerIdentitySeeder` looked up
  `'Non-Binary'` against a list containing `'Non-binary'`. The
  attach silently failed.
- **Fix**: edit Sens in the admin panel and add the `Non-binary`
  identity. The observer will rewrite the JSON. Or hand-edit the
  JSON entry's `queer_identities` array to `["Non-binary"]` and
  commit.
- **Risk**: trivial.

### Production filesystem permissions

The webserver user must have write access to
`database/data/operators.json`. On OVH this usually Just Works
because Laravel deployments hand the whole project to the
PHP-FPM user, but it's worth checking on the first post-deploy
admin save. If the writer silently fails (it currently uses
`file_put_contents` with no error check), edits land in the DB
but not in git. Consider adding an exception throw on a `false`
return from `file_put_contents` — left for the next session
because it changes the failure mode.

---

## Open questions

- **Should `OperatorJsonWriter::write()` throw on filesystem
  failure?** Currently it doesn't. If the file is read-only for
  some reason, the admin save succeeds in the DB but silently
  diverges from the JSON. A loud failure would surface this; a
  soft failure keeps the admin flow working. The user hasn't
  picked.
- **Should the JSON include reworks?** Reworks live in
  `OperatorReworkSeeder` and aren't editable from the admin panel
  today. If the panel ever grows a rework editor, that data
  should move into `operators.json` (probably as
  `"rework": {"operation_id": "Y10S2"}` per entry) rather than a
  parallel `reworks.json`.
- **Tests for the writer**: there's no Pest coverage of
  `OperatorJsonWriter` or `OperatorObserver`. The writer no-ops
  in tests by design (so the test suite doesn't clobber the
  shipped JSON), so the cleanest approach is a unit test that
  injects a temp path and verifies output shape. Worth adding
  before the next wave touches this.

---

## How to continue

```bash
git fetch origin
git checkout claude/hide-admin-panel-production-sB3Hm
git pull
```

Branch is at `46a5bcf`, ahead of `main` by 2 commits. Tests:
73 passed, 194 assertions. Nothing in flight on disk
(`git status` clean).

Suggested next batch: ship the secret-path admin hiding (the
original ask), then fix the Sens identity bug, then add unit
coverage for `OperatorJsonWriter`. All three are small,
independent, and ship cleanly together.

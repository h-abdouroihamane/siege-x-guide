# Codebase Health Report — siege-x-guide

**Date:** 2026-05-17
**Stack:** Laravel 12 (PHP 8.2+) · Inertia 2 · Vue 3.5 + TypeScript · Vite 6 · Tailwind 4 (configured, unused) · SQLite/MySQL
**Origin:** `laravel/vue-starter-kit` scaffold with a hand-built Rainbow Six Siege guide domain (operators, squads, gadgets, operations, vocabulary) bolted on top.

## Executive summary

The app works for its read-heavy public use case and the custom code is reasonably clean (no `dd()`/debug spew, consistent Composition API). But it carries three classes of serious problems:

1. **Exploitable auth/authorization gaps.** Open public registration + admin CRUD gated only by bare `auth` (no roles/policies) means any anonymous visitor can register and then create/edit/delete operators. There is also a broken duplicate admin-login path that will 500.
2. **A starter-kit skeleton that was never adopted.** TypeScript (strict, but 23/24 components untyped), Tailwind (commented out in `app.ts`), `vue-router` (dead, ships in the bundle), theme composables, and `lucide` are all dead weight that actively masks real bugs.
3. **No safety net + dangerous deploy.** Zero domain test coverage (100% starter-kit boilerplate tests), a CI lint job that can never fail, no static analysis, no type-check gate, a broken/insecure untracked Docker setup, and a deploy script that prompts to wipe the production database on every run and never installs PHP dependencies.

**Overall health: Poor-to-Fair.** Functional but fragile, with live security exposure and effectively no regression protection. The good news: the domain is small, so remediation is tractable.

---

## Planned scope change (changes priorities below)

The owner plans to **drop the Admin panel and the New/Edit Operator features entirely**, making this a purely read-only public guide with **no authentication**. This is the single highest-leverage decision in the whole rework — it deletes the code behind most Critical/High findings rather than fixing it:

- **Mooted entirely:** C1 (open registration), C2 (admin CRUD authz), C3 (broken admin login), C5 (`OperatorForm.vue` crash), H1 (dead settings routes), H2 (upload path traversal), M1 (fat write logic / transactions), M5/M6 (binding & validation on mutations), and the entire Laravel auth/settings scaffold.
- **Recommended deletions:** `app/Http/Controllers/Auth/*`, `Settings/*`, `AdminController`; operator `store/update` actions; `Create/EditOperatorRequest`; `routes/auth.php`/`admin.php`/`settings.php`; the `OperatorForm`/`NewOperatorForm`/`CreateOperator`/`EditOperator`/`admin/*` Vue pages; the `users` table + migration + seeder + factory; all starter-kit auth tests.
- **Content is seeder-only → also drop the upload code paths:** delete `Operator::getCleanName()` (`Operator.php:88` — used _only_ by the upload code, verified) and the `icon`/`portrait` `storeAs(...,'public')` blocks (gone with the deleted actions). Revert the non-standard `config/filesystems.php` `'public'` disk (`root => public_path()`) to stock — nothing writes to any disk anymore, so **no writable storage volume and no `storage:link` step are needed** in the Docker image (H10 simplification). H2 (upload path traversal) is then fully eliminated, not merely mitigated. Operator/squad/gadget images remain static files in `public/`, populated by the seeders + `scripts/getSiegeAssets.py`.
- **Still applies after the cut:** C4 (dead `vue-router`), C6 (deploy script — but it shrinks; no `migrate` auth concerns), C7 (no domain tests — now _more_ important as the only safety net), H3 (N+1), H4 (FK mismatch), H5–H7 (TypeScript), H8/H9 (CI/static analysis), H10 (Docker), H11, **H12 (asset `/build/` paths — still required for the read-only site)**, and most Medium/Low cleanup.

### Execution status — deletion phase DONE (branch `chore/drop-admin-and-uploads`)

The deletion cut has been executed as 7 commits on `chore/drop-admin-and-uploads` (not yet pushed/merged). **Resolved-by-deletion and verified** (`php artisan route:list`, `config:cache`, `npm run build`, `php artisan test` all pass):

| Finding                               | Status                                               |
| ------------------------------------- | ---------------------------------------------------- |
| C1 open registration                  | ✅ Resolved — auth scaffold deleted                  |
| C2 admin/operator CRUD authz          | ✅ Resolved — write actions + routes deleted         |
| C3 broken admin login                 | ✅ Resolved — `AdminController` deleted              |
| C4 dead `vue-router`                  | ✅ Resolved — `router.js` + dep removed              |
| C5 `OperatorForm.vue` crash           | ✅ Resolved — component deleted                      |
| H1 dead settings routes               | ✅ Resolved — route files + web.php wiring removed   |
| H2 upload path traversal              | ✅ Resolved — upload code + `getCleanName()` deleted |
| M1 fat write logic                    | ✅ Resolved — `OperatorController` now read-only     |
| M5/M6 binding/validation on mutations | ✅ Resolved — mutating endpoints gone                |
| M10 `process.env.NODE_ENV` admin link | ✅ Resolved — removed from `Navbar.vue`              |

Notes / accepted residuals: `config/auth.php` still names `App\Models\User::class` (a compile-time string, never resolved without auth — `config:cache` verified passing); the `sessions` table was **kept** (SESSION_DRIVER=database) — only `users`/`password_reset_tokens` were removed from that migration; the test suite is now empty (`tests/{Unit,Feature}/.gitkeep`), making **C7 (add domain tests) the immediate next priority**; `scripts/deploy.sh` trimmed (`migrate --force`, removed the `migrate:fresh --seed` prompt) but the asset shuffle remains pending **H12**.

**Constructive phase progress:** H12 ✅; C7 ✅; H3/M3/M4/L3 ✅; H4 ✅; N2 ✅; H5/H6/H7 ✅ (vue-tsc green, typed Inertia props, `type-check` script, `no-explicit-any` re-enabled); H8/H9 ✅ (lint.yml now fails the build — `format:check` + `lint:check` + `type-check` + PHPStan/Larastan level 5 with baseline; single formatter = Prettier, Pint & php-cs-fixer removed). **Suite: 16 passing; full local gate green.** **Still applies:** H10 (Docker), H11, and remaining Medium/Low cleanup.

**New findings surfaced during the rework:**

- **N1 (was masked, now fixed): `Operation` lacked `public $incrementing = false`** despite a string primary key — `create()` then read back an autoincrement `1`, breaking every FK to `operations` in isolation (the seeders only worked because they set ids explicitly). Fixed in the C7 task.
- **N2 ✅ (fixed): `GET /secondary-gadgets/all` was a broken public route** — mapped to a non-existent `SecondaryGadgetController@getAll`. Implemented `getAll()` mirroring the operators/squads JSON pattern (returns Attack/Defense gadget collections); now covered by the C7 suite.

The sections below describe the codebase **as it was before the cut**; read them through the lens of this status.

---

## Critical (fix first — exploitable or breaks core flows)

### C1. Open public registration on an admin-only tool

`routes/auth.php:13-18` registers `GET/POST register` under `guest` only. This is a single-admin content tool, yet **any anonymous visitor can self-register a full user account**. Combined with C2 this grants access to the entire operator/admin CRUD. Additionally `RegisteredUserController::store` redirects to `to_route('dashboard')` — **no route named `dashboard` exists** (only `admin.dashboard`), so registration itself throws `RouteNotFoundException`.
_Fix:_ remove/disable public registration (or gate it), or at minimum seed the single admin and delete the register routes.

### C2. Admin & operator CRUD authorized by bare `auth` — no policies/roles

`routes/operators.php` and `routes/admin.php` protect mutating routes with `->middleware('auth')` only. There is **no Gate, Policy, or role check anywhere** (`AppServiceProvider` is empty; no `app/Policies`). `CreateOperatorRequest::authorize()` and `EditOperatorRequest::authorize()` both hardcode `return true` (verified). Any authenticated user — including one self-registered via C1 — can create/edit/delete operators.
_Fix:_ add an `is_admin` flag/role + a policy or middleware on the operator/admin route groups; make the FormRequest `authorize()` enforce it.

### C3. Broken duplicate admin-login path (guaranteed 500 + no rate limiting)

`AdminController::authenticate()` (`app/Http/Controllers/AdminController.php:28-46`):

- Declares `: RedirectResponse` but the class **never imports `Illuminate\Http\RedirectResponse`** (verified) → fatal `Error: Undefined type` whenever the method is invoked.
- `return redirect()->intended('admin.dashboard')` redirects to the literal path `/admin.dashboard` (404), not `route('admin.dashboard')`.
- Uses inline `$request->validate()` with **no brute-force throttling**, unlike the correct path (`AuthenticatedSessionController` + `LoginRequest`, which has `RateLimiter` 5-attempt throttling).
  This is a second, inferior, attacker-reachable login endpoint.
  _Fix:_ delete `AdminController::authenticate/index/dashboard` duplication; route `POST /admin/login` to the hardened `AuthenticatedSessionController`.

### C4. `vue-router` is entirely dead code and ships to users

`resources/js/router.js` defines a `createMemoryHistory` router; **nothing imports it** (no `useRouter`, `RouterView`, `.use(router)` anywhere). The app is 100% Inertia. `vue-router@^4.5.1` is a production dependency for nothing, and the two "routed" components are also rendered as Inertia pages (would conflict if wired).
_Fix:_ delete `router.js` and the `vue-router` dependency.

### C5. `OperatorForm.vue:22` runtime crash on Edit

```js
operation_id: operator.operation.id ?? operations[0].id,
```

`operations` is undefined in scope (the prop is `props.operations`); `operator.operation` is dereferenced unguarded. Editing an operator whose operation is null throws `ReferenceError` and the form fails to mount.
_Fix:_ use `props.operations` and null-guard `operator.operation`.

### C6. `scripts/deploy.sh` is a destructive, fragile production deploy script

The deploy script (untracked; targets `/var/www/siege-x-guide.alsagone.ovh`, pulls from `git@gitlab.com:alsagone/siege-x-guide.git`) has multiple production-breaking issues:

- **Interactive `migrate:fresh --seed` prompt at the end of every deploy** (`deploy.sh:30-36`) — one careless `Yes` **drops and reseeds the entire production database**. This must never live in a deploy script.
- **No `set -euo pipefail`** — every step continues on failure. If `git pull` or `cd` fails, it still runs `npm run build` / `php artisan migrate` against the wrong code or wrong directory. `cd "${varFolder}"` (`deploy.sh:6`) has no success guard before `git reset --hard HEAD` (`:7`) — a failed `cd` runs a hard reset in whatever the host's cwd is.
- **`php artisan migrate` without `--force`** (`deploy.sh:11`) — in production this prompts for confirmation and will hang or abort the deploy.
- **Composer dependencies are never installed** — only `npm install` (`:9`, not `npm ci`); no `composer install --no-dev --optimize-autoloader`. Any new/changed PHP dependency breaks production on deploy.
- **No maintenance mode / cache rebuild** — no `artisan down`/`up`, no `config:cache`/`route:cache`/`view:cache`/`optimize`; users are served half-built assets mid-deploy and stale cached config after.
- Fragile manual asset shuffling with hardcoded filenames (`deploy.sh:14-27`) that must be hand-synced with Vite output; unmatched globs pass literal strings. **This block exists only to work around H12 (public assets wrongly prefixed with `/build/`) — fixing H12 removes the need for it entirely.** Dead unused `gitUrl` variable (`:3`). Deploys straight off `main` with no tag/release pinning or rollback path.
  _Fix:_ remove the `migrate:fresh` block entirely; add `set -euo pipefail`; add `composer install --no-dev --optimize-autoloader`; use `npm ci`; `migrate --force`; wrap in `artisan down`/`up` + cache rebuild; commit the script under version control.

### C7. Zero domain test coverage

The entire suite is unmodified `laravel/vue-starter-kit` boilerplate (auth, profile, dashboard, `ExampleTest`). **No tests for any domain controller or model** (Operator/Squad/SecondaryGadget/Admin/Vocabulary), and **no domain factories** (`database/factories/` has only `UserFactory`). `tests/TestCase.php` and `tests/Pest.php` still contain the stub placeholders. Combined with C1–C3, regressions to auth/authz would go completely undetected.
_Fix:_ add factories for the domain models and feature tests for the operator/admin CRUD and authorization, starting with the C1–C3 paths.

---

## High (significant risk or broken features)

| ID  | Finding                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Location                                              |
| --- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------- |
| H1  | **`routes/settings.php` is never `require`d in `web.php` (verified)** → the entire settings/profile/password/appearance feature, its controllers, FormRequests, and Inertia pages are dead and unreachable.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | `routes/web.php`                                      |
| H2  | **File-upload path traversal/overwrite.** `OperatorController` writes icons/portraits to `getCleanName().'.png'`; `getCleanName()` is `iconv(...strtolower(name))` and `name` is validated only as `string` (no charset/regex). A name like `../../x` escapes the directory; a rename collision silently overwrites another operator's images (`EditOperatorRequest` lacks the `unique` rule that `CreateOperatorRequest` has).                                                                                                                                                                                                                                                                                                                                                                                                                                                                       | `OperatorController.php:116-128,197-209`              |
| H3  | **N+1 queries.** `getAll` eager-loads roles/squad/operation/queerIdentities but **not `rework`**; `OperatorResource`/`Operator::getOperation()` then fires per-row queries. `SecondaryGadgetResource` loads `operators` with no eager load and sorts via `compareReleaseDate()` which re-queries inside an O(n log n) comparator.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     | `OperatorController.php:18-28`, `Operator.php:82-119` |
| H4  | **FK type mismatch.** `operator_rework.operation_id` is `ulid` (char(26)) but `operations.id` is a `string` holding values like `Y10S3`. Fragile/incorrect by driver.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | `2025_11_18_..._create_operator_rework_table.php`     |
| H5  | **TypeScript is decorative.** `tsconfig` is `strict`, but only 1/24 `.vue` files uses `lang="ts"`; props are untyped arrays; `@typescript-eslint/no-explicit-any` is disabled. Inertia page props are 100% untyped (`usePage()` with no generic); the `SharedData`/`User` types are stale starter-kit definitions that don't match this app.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | `eslint.config.js:25`, all pages                      |
| H6  | **`vue-tsc --noEmit` fails immediately** (`TS2688: Cannot find type definition file for 'vue/tsx'`, from `tsconfig.json` `"types"`). There is no `type-check` script and no CI step — type safety is entirely unenforced.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | `tsconfig.json:44-48`                                 |
| H7  | **`Operator` TS class lies.** `operationReleaseDate: string` is assigned a `Date`; field declared `queerIdentites` (typo) but assigned `queerIdentities`. Only "works" because consumers are untyped (H5).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | `resources/js/scripts/operator.ts:21,25,48,52`        |
| H8  | **CI lint job is a no-op.** `lint.yml` runs `prettier --write` / `eslint --fix` (auto-fix, exit 0) with the commit step commented out, so it **can never fail the build**. No `format:check`/`pint --test`. No frontend type-check in CI.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | `.github/workflows/lint.yml:27-39`                    |
| H9  | **No static analysis.** No PHPStan/Larastan, no `pint.json` (CI runs Pint in fix mode → can't fail), no type-coverage. Three competing PHP formatters configured: Pint + `friendsofphp/php-cs-fixer` + `@prettier/plugin-php`.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | composer/prettier config                              |
| H10 | **Docker setup broken & insecure (untracked) — intended to ultimately replace `deploy.sh` (C6).** Until that migration lands, both exist; the Docker image must absorb the deploy responsibilities `deploy.sh` does today (`composer install --no-dev`, `npm ci && npm run build`, `migrate --force`, cache rebuild) and **must not** carry over the `migrate:fresh --seed` prompt. The H12 asset fix is a prerequisite for _either_ path — Docker will hit the same `/build/` 404s otherwise. Single-stage, runs as root, mounts host `~/.ssh` into the web container, bakes `key:generate`+`config:cache` at build time, no `.dockerignore` (`COPY . .` first), missing referenced SSL certs, filename-casing mount bug (`phpMyAdmin.conf` vs `phpmyadmin.conf`), app/nginx working-dir mismatch (`/app` vs `/var/www`), MySQL server installed _inside_ the app image _and_ as a separate service. | `Dockerfile`, `docker-compose.yml`, `docker-compose/` |
| H12 | **Public assets referenced via Vite `BASE_URL` (root cause of the C6 deploy hack).** ~10 files build image URLs from `import.meta.env.BASE_URL`, which resolves to `/build/` in production but `/` in dev — so the ~400 static images in `public/` 404 on the live site unless physically moved into `public/build/` (`deploy.sh:14-27`). `IntroCard.vue:6,12,18,28,37` additionally hardcodes `/build/...`. These are static Laravel `public/` assets, not Vite-managed assets, and must not carry the `/build/` prefix. _Fix:_ introduce one shared `publicAsset()` helper returning root-absolute (`/`) paths (also resolves M8 duplication), fix the `IntroCard` hardcodes, then delete `deploy.sh:14-27` entirely (assets are already deployed by `git pull`). Accept loss of content-hashing for static game art, or add cache headers/version query later.                                     | `scripts/operator.ts:1`, `IntroCard.vue`, +~8 files   |
| H11 | **Half-wired "show queer identities" feature.** `OperatorsView.vue:125` binds `:show-queer` but `OperatorCard` doesn't declare the prop; toggle is inert and `OperatorCard` always renders flags. Leftover `console.log` at `OperatorsView.vue:63`.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | `OperatorsView.vue`, `OperatorCard.vue`               |

---

## Medium

- **M1. Fat `OperatorController` with no transactions.** `store()`/`update()` duplicate ~50 lines (operation create, squad attach, identity/role sync, dual file storage) with no `DB::transaction` — a mid-write failure orphans `Operation`/`Operator` rows. Extract to a service/model methods.
- **M2. Mass-assignment posture.** `Operation` is `$guarded = []`; `Squad/Role/QueerIdentity/SecondaryGadget/Rework` declare neither `$fillable` nor `$guarded`. Prefer explicit `$fillable`.
- **M3. Missing casts.** `Operation::release_date` is a `date` column with no cast → raw string, not `Carbon`. No model uses `casts()` except `User`.
- **M4. Missing DB indexes / constraints.** `operators.side`, `secondary_gadgets.side`, `operator_squad.rank` are filtered/range-queried but unindexed. Pivot tables have no composite PK/unique → duplicate rows possible. `queer_identities.name`/`secondary_gadgets.name` looked up by name but not unique (unlike `roles`/`squads`).
- **M5. Validation gaps.** Inline `validate()` instead of FormRequests in `AdminController`, `RegisteredUserController`, `ProfileController::destroy`; `OperatorController::selectPost` reads `operatorName` with **no validation**; `EditOperatorRequest` missing the `unique:operators,name` rule.
- **M6. Route-model-binding inconsistency.** `operator.edit` uses implicit binding; `operator.update` takes a string and does manual `findOrFail`.
- **M7. CSS architecture is two un-integrated systems.** ~15 hand-written plain-CSS files (some imported globally, some per-component as fake `lang="scss"`) **plus** a full Tailwind 4 + shadcn token layer in `app.css` that is **commented out in `app.ts:1`** so never loaded. `@tailwindcss/vite`, `tw-animate-css`, `tailwind-merge`, `prettier-plugin-tailwindcss` are all dead weight. Pick one system.
- **M8. Heavy frontend duplication.** `getOperatorIcon`/`getGadgetLogo`/`publicPath` reimplemented in 4+ components; `getAltText`/`copyAltText`, screenshot-mode UI, and most form fields duplicated verbatim between `SquadsView`/`GadgetTable` and `OperatorForm`/`NewOperatorForm`. Extract composables/shared components.
- **M9. Dead assets/code.** `footer.css` (0 bytes), `app.css` (186 unused lines), `menu_background.JPG` in the CSS dir, `useInitials`/`useAppearance` composables, stale `types/index.d.ts`, `lucide-vue-next` (zero imports — all icons inline SVG).
- **M10. `Navbar.vue:4` uses `process.env.NODE_ENV`** (Node global, not Vite-safe) to gate the admin link; should be `import.meta.env.DEV`.
- **M11. Platform-locked deps.** `optionalDependencies` hard-pin `@rollup/rollup-linux-x64-gnu@4.9.5` (exact) etc. → builds break on arm64/macOS/musl. Redundant stale `sass-loader@10` (webpack-era) alongside `sass`/`sass-embedded` in a Vite project.
- **M12. Weak pre-commit & stock `.env.example`.** Husky/lint-staged runs Prettier only (no ESLint/Pint/types/tests). `.env.example` is unmodified stock (`APP_NAME=Laravel`, `DB_CONNECTION=sqlite`) and inconsistent with the MySQL-based docker-compose. (No real secrets committed — good.)
- **M13. PHP version spread.** composer `^8.2` / CI `8.4` / Docker `8.3` — three different minors across environments.

---

## Low

- **L1.** Empty boilerplate: `AppServiceProvider`, base `Controller`, empty `withExceptions` in `bootstrap/app.php` (no Inertia error mapping, no `Model::preventLazyLoading` — which would have surfaced H3).
- **L2.** `Operator::squad` is `belongsToMany` but universally treated as single; `addToSquad` rank-init edge bug always sets the first squad assignment to `rank=1`. `Operation::operators` relationship is malformed (`belongsToMany` using the `operators` table as its own pivot) — unused but wrong.
- **L3.** `compareReleaseDate` dereferences `getOperation()->year` with no null guard → fatal if an operator has neither rework nor valid operation.
- **L4.** Accessibility: empty `<th>` headers on data tables, `href="#"` credit links, click-only `<img>` operator selection (no keyboard), blocking `alert()` for feedback, missing `Navbar` import in `OperatorSelection.vue`, unlabeled logo SVGs.
- **L5.** Style consistency: `let x = ref()` instead of `const`, explicit `.ts` in imports, kebab/camel event-name mixing, inline hardcoded brand colors despite an (unused) token system.
- **L6.** CI installs xdebug but collects no coverage; `lint.yml` grants unused `contents: write`.
- **Positives:** No `v-html`/XSS surface; external links use `rel="noopener noreferrer"`; lockfiles committed and consistent; no secrets committed; `tests.yml` does run the Pest suite + build on clean in-memory SQLite; consistent `<script setup>` Composition API; clean custom PHP (no debug leftovers).

---

## Recommended remediation order

1. **Security via deletion (C1, C2, C3, C5):** per the planned scope change, **remove** the Admin panel, operator write actions, and the entire auth/settings scaffold rather than hardening them. This is faster and safer than fixing the authz model and closes C1/C2/C3/C5/H1/H2/M1 in one move. (If any admin capability must be retained later, _then_ add a role + policy + rate-limited single login.)
2. **Broken features (C4, C5, H1, H11, H12):** delete `vue-router`/`router.js`, fix `OperatorForm.vue:22`, wire or delete `routes/settings.php`, finish or remove the show-queer toggle, and fix the public-asset `/build/` prefix (H12) — a prerequisite for the deployment rework below.
3. **Safety net (C7, H6, H8, H9):** add domain factories + feature tests; add a `type-check` script and fix the `vue/tsx` tsconfig error; make CI lint use `format:check`/`pint --test`/`eslint` (no `--fix`) and add type-check; add Larastan + `pint.json`.
4. **Data integrity (H2, H3, H4, M1, M3, M4):** sanitize upload filenames + add `name` constraints, eager-load `rework`, wrap multi-write `store()` in a transaction, fix the `operator_rework` FK type, add casts and indexes.
5. **Deployment rework (C6 → H10):** harden the Docker image to fully replace `deploy.sh` — multi-stage, non-root, `composer install --no-dev --optimize-autoloader`, `npm ci && npm run build`, `migrate --force`, cache rebuild, no `migrate:fresh`, no host `~/.ssh` mount, runtime env (not build-time `key:generate`/`config:cache`), add `.dockerignore`. Then delete `deploy.sh` and commit the Docker setup.
6. **Cleanup (H5, H7, M7–M11):** type the components and Inertia props, commit to one CSS system, extract duplicated composables, remove dead deps/assets.

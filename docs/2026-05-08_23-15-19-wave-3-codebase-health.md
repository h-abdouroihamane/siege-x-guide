# HANDOFF.md

State of the codebase health refactor as of branch
`claude/assess-codebase-health-Z8ngO` and main commit
`5474d0e` (after PR #7).

This file is a working handoff for the next session to pick up
the remaining work. Read it before continuing the wave.

---

## What's done

The original codebase health audit (Wave 1) found ~30 issues across
P0–P3. Most are now fixed and merged across four PRs:

- **PR #4** — Wave 3b Batch 1 (layout primitives, `PageLayout`
  extraction, asset rename)
- **PR #5** — Wave 3b Batch 2 (operator pages and components)
- **PR #6** — Auth redirect bugfix (`AdminController::authenticate`
  was passing a route name where `redirect()->intended()` wanted
  a URL string)
- **PR #7** — Wave 3b Batch 3 (finish CSS-to-Tailwind, drop SCSS)

Plus the earlier waves (1, 2, 2.5, 3a) were merged together as
the first big PR before this branch existed.

### Concrete outcomes

- **Tailwind v4 migration complete.** Every page and component
  uses inline utilities. `resources/css/` shrank from 14 files to
  4 (each with a clear reason to exist — `app.css`, `button.css`,
  `fonts.css`, `pride.css`).
- **SCSS toolchain dropped** (`sass`, `sass-embedded`,
  `sass-loader` removed from `package.json`). Zero
  `<style lang="scss">` blocks remain.
- **`@theme inline` tokens** in `app.css`: `--font-sans` (FK
  Grotesk), `--font-display` (GT America Compressed Bold),
  `--font-gt-america` (regular GT America), `--font-mono`
  (Simplon Mono), `--color-signal` (`#ff4b3c`).
- **56 Pest tests** covering auth middleware, operator CRUD,
  FormRequest validation, public listing pages, model scopes,
  casts, and comparators. All green.
- **Typed `defineProps`** on every Vue component; domain TS
  types live in `resources/js/types/domain.ts`.
- **AGENTS.md §1 / §10 / §13** updated for dark-only (no `dark:`
  Tailwind variants — single dark palette, plain utilities).
- **`README.md` and `DEVELOPMENT.md`** added; the latter is a
  Bazzite/Sail-on-Podman runbook with seven specific error
  messages and their fixes.

---

## What's left

Punch list with file paths and analysis already done. Pick any
order — they're independent.

### Real bugs (worth fixing)

#### 1. `OperatorData.id` not exposed by `OperatorResource`

- **File**: `app/Http/Resources/OperatorResource.php`
- **Symptom**: `OperatorForm.vue:15` reads `operator.id`, line 38
  has `<input type="hidden" name="id" :value="operator.id">` —
  both undefined at runtime because the resource doesn't include
  `id` in `toArray()`.
- **Marker in code**: there's a `// TODO(wave-3):` comment in
  `OperatorForm.vue` lines 5–7 calling this out.
- **Fix**: add `'id' => $this->id` to `OperatorResource::toArray`,
  then add `id` to `OperatorData` in
  `resources/js/types/domain.ts`. Drop the TODO comment.
- **Risk**: low; pure backend additive change.
- **Owner**: `backend-dev`.

#### 2. `/squads` and `/secondary-gadgets` routes not named

- **Files**: `routes/squads.php`, `routes/secondaryGadgets.php`
- **Symptom**: `Navbar.vue` has these two as the only hard-coded
  string `href`s left (with comments flagging them); everything
  else uses `route(...)`. Was flagged in Wave 3a.
- **Fix**: add `->name('squads.index')` (or similar) on the
  matching `Route::get('/squads', ...)` line in each file;
  update `Navbar.vue` to use `:href="route('squads.index')"`
  / `:href="route('secondary-gadgets.index')"`; drop the TODO
  comments.
- **Risk**: trivial.
- **Owner**: `backend-dev` for the routes, `frontend-dev` for
  the `Navbar.vue` edit.

#### 3. `--popover-foreground` missing `hsl()` wrapper

- **File**: `resources/css/app.css` (in the `:root` block, the
  popover-foreground line is `--popover-foreground: 0 0% 98%;`
  — should be `hsl(0 0% 98%)`).
- **Symptom**: surfaced way back in Batch 0 commit message; no
  consumer actually breaks because `--popover-foreground` isn't
  used in any visible component yet, but the value is malformed.
- **Fix**: wrap in `hsl(...)`. One-line change.
- **Risk**: zero.

#### 4. Auth Pest test doesn't assert redirect URL

- **File**: `tests/Feature/AdminAuthTest.php`
- **Symptom**: the test "redirects to dashboard after valid
  login" passed both before and after the PR #6 fix, because it
  only asserts the redirect status code (302) — not the
  destination URL. That's exactly why the
  `redirect()->intended('admin.dashboard')` bug shipped.
- **Fix**: add `->assertRedirect(route('admin.dashboard'))` (or
  the equivalent assertion on the response location header) to
  the relevant Pest test. While there, audit other Pest tests
  for the same gap — anywhere we assert a redirect, also assert
  the destination.
- **Risk**: low; the existing redirect actually goes to the
  right place after PR #6, so the test will go from "passing
  by accident" to "passing for the right reason."
- **Owner**: `qa-tester`.

### Cleanup (lower priority but well-defined)

#### 5. `useInitials` composable orphan

- **File**: `resources/js/composables/useInitials.ts`
- **Symptom**: zero callers in the project. Originally from the
  Laravel + Breeze + Vue starter kit. Lead-dev flagged it as a
  P3 orphan in the original audit.
- **Fix**: `git rm` the file. Confirm no callers via grep
  (`grep -rn "useInitials" resources/js`).
- **Risk**: zero (verify zero callers first).

#### 6. `useAppearance` composable + `HandleAppearance` middleware orphan

- **Files**:
    - `resources/js/composables/useAppearance.ts`
    - `app/Http/Middleware/HandleAppearance.php`
    - Wherever `HandleAppearance` is registered (likely
      `bootstrap/app.php` or a service provider).
- **Symptom**: orphaned by Wave 3b Batch 0 when `initializeTheme()`
  was removed from `app.ts`. The composable has no callers; the
  middleware sets a cookie that nothing reads.
- **Fix**: `git rm` both files; remove the middleware registration
  from `bootstrap/app.php`'s `withMiddleware` callback (or
  wherever it's listed).
- **Risk**: low. Sanity-check by grepping for references.

#### 7. `<div class="row">` in `NewOperatorForm.vue`

- **File**: `resources/js/components/NewOperatorForm.vue:42`
- **Symptom**: wraps year + season form rows. The `.row` class
  is not defined anywhere — the wrapper is just a `<div>` and
  year/season stack vertically. Probably the original author
  intended `display: flex; flex-direction: row;`.
- **Fix**: replace with `class="flex flex-row gap-2"` (or whatever
  spacing fits the design) so year and season sit side-by-side.
  This is a small **design change**, not just a refactor — should
  be visually verified.
- **Risk**: low; visual impact only.

#### 8. `Navbar.vue` is 300 lines, over the §3 250-line cap

- **File**: `resources/js/components/Navbar.vue`
- **Symptom**: desktop and mobile menus duplicate the same 7 nav
  link entries with identical class strings. A `Navbar/` folder
  with a `NavLink` sub-component would halve the line count
  cleanly.
- **Fix**: per AGENTS.md §3, split into:
    ```
    resources/js/components/Navbar/
        index.vue            # composes the rest, default export
        NavLink.vue          # one nav-item with active-state styling
        Hamburger.vue        # the mobile toggle button (optional)
    ```
- **Risk**: medium; it's a real refactor with possible regressions
  on the responsive behavior. Would benefit from a `lead-dev`
  read first or a single-file dispatch to `frontend-dev`.

### Nice-to-have (P3 from original audit; skip unless bored)

#### 9. `Operator` class in `operator.ts` → interface + pure functions

- **File**: `resources/js/scripts/operator.ts`
- **Symptom**: the class has no private state. `isAttacker()`,
  `isDefender()`, `compareName()`, `compareRelease()` are pure
  functions of the object's fields. The `OperatorsView.vue`
  manually maps the API response into class instances at
  initialization (lines 13–28) — that step would disappear if
  the type was just an interface matching the resource shape.
- **Fix**: convert the class to `interface Operator { ... }` plus
  module-level `compareName`, `compareRelease`, `isAttacker`,
  `isDefender` functions. Update `OperatorsView.vue` and
  `OperatorCard.vue` (and any other consumers) to use the new
  shape.
- **Risk**: medium-high; ripples through the operator views.

---

## Suggested PR grouping

A clean way to pack the punch list into reviewable PRs:

- **PR A — Wave 3c proper** (small, focused cleanup):
  items 3, 4, 5, 6, 7. About 8–10 files.
- **PR B — Backend route naming**: item 2. Tiny PR. Could pair
  with the `Navbar.vue` Ziggy update.
- **PR C — Resource id exposure**: item 1. Tiny PR, but worth
  isolating since it touches the typed domain layer. Verify
  the EditOperator flow end-to-end after.
- **PR D — Navbar split** (optional): item 8. Medium-effort
  refactor. Split into a separate PR for clean review.
- **PR E — Operator class to interface** (optional): item 9.
  Larger surface area; only do if there's appetite.

---

## Architectural patterns established

So the next session follows the same conventions:

### CSS migration

- **Inline Tailwind on the element** is the default. Reach for
  a scoped `<style>` block only when the rule earns it (§10):
  combinatorial state matrices, `::-webkit-scrollbar`
  pseudo-elements, `grid-template-areas`, hover-state combinator
  chains across multiple nested elements.
- **No `dark:` variants.** Site is dark-only; the dark palette is
  _the_ palette via plain utilities sourced from `@theme inline`
  tokens.
- **`@source '../js/**/\*.{vue,ts,js}'`** in `app.css` is the
  belt-and-suspenders directive that ensures Tailwind picks up
  arbitrary classes. Don't remove it.
- **`app.css` MUST be imported by `app.ts`.** Removing that
  single line takes the entire Tailwind layer offline. (Was the
  silent killer of Batch 1's first attempt.)

### Migration discipline

- **One file/component per dispatch**, with the user visually
  verifying between each step. The big-batch attempt (the
  reverted Batch 1) failed because the surface was too large to
  debug when something broke.
- **Test all four signals after every step**: `npm run lint`,
  `npx vue-tsc --noEmit`, `npm run build`, `php artisan test`.
- **Before deleting any CSS file, grep the entire codebase for
  `@use` and class-name references.** The component dir alone
  isn't enough — pages and pre-existing partial migrations may
  still reference it. (Was the cause of the post-PR-#5 CI break.)

### Pitfalls discovered

- **Vite asset resolution is case-sensitive** — `.JPG` (uppercase)
  was failing through the asset pipeline; renamed to `.jpg`.
- **Body bg + negative z-index trap** — `body { bg-background }`
  combined with a fixed `[z-index:-1]` element will _cover_ the
  fixed element if `<html>` has no background. Move
  `bg-background` to `<html>` instead, OR use a non-negative
  `z-index` for the bg layer. Currently `bg-background` is on
  `<html>` in `@layer base`.
- **Vue 3 multi-root templates** are fine; Inertia doesn't
  require a single wrapper `<div>`.
- **`@use` is per-component scope** — when you delete a file
  consumed by multiple components, each one needs its own
  fix-or-removal step.
- **Tailwind v4 utility scope** beats `@layer base` rules. If you
  put element rules in `@layer base`, individual elements can
  override via `class="..."` without `!important`.

### Sail / Bazzite setup

Captured in full in `DEVELOPMENT.md`. The seven errors I hit
during initial setup, in order:

1. `rootlessport cannot expose privileged port 80` — set
   `APP_PORT=8080`.
2. `Could not open input file: artisan` — add `:z` SELinux
   relabel to the volume mount.
3. `Error: Can't drop privilege as nonroot user` — switch to
   rootful Podman socket; Sail's start-container script needs
   real root.
4. `SQLSTATE[HY000] [2002] No such file or directory` — set
   `DB_HOST=mysql` (the service name, not localhost).
5. `SQLSTATE[HY000] [2002] Connection refused` — wait 30s for
   MySQL to fully initialize on a fresh volume.
6. Background image not loading — was a stacking-context bug,
   resolved by moving `bg-background` to `<html>`.
7. CI build failure on PR #5 — stale `@use 'operator-card.css'`
   in `admin/Dashboard.vue` and `OperatorSelection.vue` after
   the file was deleted.

---

## Quick reference

- **Branch**: `claude/assess-codebase-health-Z8ngO`
- **Latest commit on branch**: `d667b34` (matches main as of
  PR #7 merge)
- **Working tree**: should be clean
- **Test count**: 56 Pest tests, 149 assertions, all green
- **Lint**: clean
- **Typecheck**: only the pre-existing `TS2688: Cannot find
type definition file for 'vue/tsx'` from `tsconfig.json:46`
  (orthogonal to this work)
- **Build**: clean, no warnings worth chasing

To resume: read this file, pick a punch-list item, dispatch
following the patterns above. The branch can keep accumulating
commits or you can branch off `main` per item — your call based
on PR-grouping preference.

# AGENTS.md

Guidance for any agent (Claude Code or human) working in this repository.
Read this **before** writing or reviewing code.

---

## 1. Project at a glance

**Siege X Guide** is a community guide for Rainbow Six Siege X. It
catalogs operators, their gadgets and roles, the squads they belong to,
and surfaces queer representation among the roster. Public visitors
browse and filter; a single admin signs in to add or edit operators.

Key facts that drive every decision below:

- **Domain / audience**: Rainbow Six Siege players. Tech-literate
  gamers; expect strong opinions on terminology and accuracy.
- **UI language**: English only (`APP_LOCALE=en`). US conventions.
- **Auth**: Laravel session-based auth (single admin user, no
  registration). Login at `/admin/login`.
- **Roles**: `admin` (authenticated editor) | `guest` (anonymous
  visitor, read-only).
- **Persistence**: MySQL 8 (database `siege_x_guide`), accessed via
  Laravel 12 Eloquent. Schema lives in `database/migrations/`.
- **Hosting**: deployed on OVH.
- **Theming**: dark only. The `useAppearance` composable still
  exists but is not surfaced in the UI; the visual reference is
  the official R6 Siege operators page on ubisoft.com. Must meet
  WCAG AA.
- **Notifications**: none.

---

## 2. Tech stack

Always install the **latest stable** version of each dependency. Do not
pin to outdated majors without a written reason.

- Language / runtime: PHP 8.2+, Node 20+
- Backend framework: Laravel 12
- Server↔client bridge: Inertia.js (`inertiajs/inertia-laravel` +
  `@inertiajs/vue3`)
- Frontend framework: Vue 3.5 + TypeScript 5
- Build tool: Vite 6 (`laravel-vite-plugin`,
  `@vitejs/plugin-vue`)
- Styling: Tailwind CSS v4 + `tw-animate-css`. Class merging via
  `clsx` + `tailwind-merge`; component variants via
  `class-variance-authority`.
- Headless UI primitives: Reka UI (`reka-ui`)
- Routing helpers: Vue Router 4 (client), Ziggy (`ziggy-js`) for
  server route names in TS
- State / data: Inertia page props. Composables in
  `resources/js/composables/`.
- Icons: `lucide-vue-next`
- Tests: Pest 3 (PHP). No JS test runner is installed — add Vitest
  only with an ADR from `lead-dev`.
- Lint / format: ESLint 9, Prettier 3 (with
  `prettier-plugin-organize-imports` and
  `prettier-plugin-tailwindcss`), Laravel Pint, PHP-CS-Fixer.

No additional UI / state libraries unless justified in an ADR by
`lead-dev`. Utility helpers that exist in three places or fewer should
not be promoted to a dependency — write the four-line helper inline.

---

## 3. File organization

```
app/
  Http/
    Controllers/        # one per resource (OperatorController, …)
    Middleware/         # HandleAppearance, HandleInertiaRequests
    Requests/           # FormRequest validators
    Resources/          # API resources / transformers
  Models/               # Eloquent models (Operator, Squad, Role, …)
  Providers/
database/
  migrations/           # checked-in schema, forward-only
  factories/
  seeders/
resources/
  js/
    pages/              # Inertia page components, one per route
      admin/
    components/         # reusable Vue components
    composables/        # Vue composables (useAppearance, useInitials)
    types/              # TS types (globals, ziggy)
    scripts/
    app.ts              # client entry
    ssr.ts              # SSR entry
  css/
  views/                # Blade entrypoint for Inertia
routes/                 # one file per resource group
  about.php  admin.php  auth.php  console.php  home.php
  operators.php  secondaryGadgets.php  settings.php
  squads.php  vocabulary.php  web.php
tests/
  Feature/
  Unit/
```

### Per-file rules

- **~250 lines soft cap.** When a Vue component, controller, or model
  grows past that, **split into a folder** and colocate sub-components
  / sub-modules:

    ```
    OperatorForm/
      index.vue            # composes the rest, default export
      OperatorFormHeader.vue
      OperatorFormGadgets.vue
      OperatorFormQueerIdentity.vue
      types.ts             # if needed
    ```

- **~80 characters per line.** Documentation examples should be
  shorter, generally no more than 70 characters.
- One responsibility per file. If a controller mixes data fetching,
  validation, and presentation logic, it's probably three pieces
  (FormRequest + Controller + Resource).
- No barrel files re-exporting everything; import from the explicit
  path.

---

## 4. Routing

- **Server routes** are declared in `routes/*.php`, one file per
  resource group, all wired up via `bootstrap/app.php`. Keep them
  grep-friendly: a single controller per group, `->name(...)` on every
  route so Ziggy/Inertia can find them.
- **Protected routes** sit under the `auth` middleware (see
  `routes/admin.php`). Server-side authentication is the source of
  truth.
- **Inertia pages** live in `resources/js/pages/`. The page component
  filename mirrors the route's purpose (`admin/Dashboard.vue`,
  `OperatorsView.vue`).
- Client-side route helpers come from Ziggy (`route('admin.dashboard')`
  in TS). Do not hard-code URLs in components.

---

## 5. Authentication

- Single admin user; no public registration.
- Login form at `/admin/login` posts to `AdminController@authenticate`.
  Laravel's session guard handles the rest.
- Sign-out clears the session and redirects to the public landing
  page. Never store auth tokens in `localStorage` manually.
- The `auth` middleware guards admin routes server-side; the UI hides
  admin actions for guests purely for UX.

---

## 6. Roles & permissions

This project has two effective roles:

- `admin` — authenticated user. Can create, edit, and delete
  operators, squads, gadgets, etc.
- `guest` — unauthenticated visitor. Read-only access to all public
  pages.

> ⚠️ The `roles` table in the database is **gameplay roles** for
> Rainbow Six operators (Attack/Defense classes), not user
> permissions. Do not confuse the two when reading migrations or
> models.

- Server-side authorization is enforced by Laravel middleware (`auth`)
  and FormRequest authorization. **Default-deny** on any new admin
  action.
- Client UI may hide admin-only buttons via the Inertia-shared `auth`
  prop, but the server is the source of truth.

---

## 7. Database & migrations

- All schema changes go through **Laravel migrations checked into
  `database/migrations/`**. No clicking in a DB GUI.
- Migration naming follows Laravel's convention
  (`YYYY_MM_DD_HHMMSS_description.php`); `php artisan
make:migration` produces it.
- **Forward-only.** Migrations are immutable once shipped. To fix a
  mistake, write a new migration that corrects it — do not edit a
  deployed migration.
- Eloquent models (`app/Models/`) declare relationships and casts;
  validation lives in `app/Http/Requests/` FormRequests, not in the
  controller.
- Migrations are owned by `database-engineer`.
- TypeScript types for Inertia page props live in
  `resources/js/types/`. When a model's shape changes, update the
  matching TS type in the same change.

---

## 8. Security policy

- No credentials in committed files. `.env` is git-ignored; copy from
  `.env.example`.
- Validate every server-side input via FormRequest. Never trust the
  client.
- HTTPS only in production.
- Sanitize anything rendered as HTML. Vue templates escape by default
  — never use `v-html` on user-supplied or admin-supplied content
  without an explicit sanitizer.
- No PII in logs, analytics, or error trackers.
- CSRF middleware stays on for all state-changing routes (Laravel
  default).

---

## 9. UI / UX

### 9.1 Language

English only, US conventions. Centralize repeated user-facing strings
in a shared constant or component prop rather than duplicating them
across components. There is no full i18n layer today; introducing one
requires an ADR from `lead-dev`.

### 9.2 Audience

Rainbow Six Siege players. Assume familiarity with the game's
vocabulary (operator, gadget, attacker, defender, squad). Spell out
internal-only acronyms on first use; do not over-explain in-game
terms.

Sensible baseline:

- Plain language. Base font ≥ 16 px, body line-height ≥ 1.5.
- Click/tap targets ≥ 44×44 px.
- Buttons have **icon + text label** — never icon-only for actions.
- Confirm destructive actions (deleting an operator, removing a
  squad) with explicit wording.
- Error messages: what went wrong + how to fix it, in plain language.
  No bare error codes.

### 9.3 Accessibility — WCAG 2.1 AA

- **Keyboard**: every interactive surface reachable via `Tab`,
  activatable via `Enter` / `Space`. Logical tab order.
- `<label for="id">` on every input. **Never** use placeholder as
  label.
- `aria-*` only where role alone doesn't convey meaning. Reka UI
  primitives already wire most ARIA — don't override unless you know
  why.
- Modals / dialogs: `role="dialog"` + `aria-modal="true"` + focus
  trap (Reka UI's `Dialog` provides this; use it).
- Visible focus ring at all times. Don't suppress `:focus-visible`.
- Contrast ≥ 4.5:1 for text, ≥ 3:1 for UI components.
- Never convey information by color alone (pair with icon, text, or
  pattern). Critical for queer-flag indicators on operator cards.

---

## 10. Styling conventions

- Tailwind utility classes **inline on the component**. Avoid
  abstractions (`@apply`, deep `<style>` blocks, runtime CSS-in-JS)
  until they earn their keep.
- Conditional classes: `clsx` + `tailwind-merge` (already
  installed). For reusable variant patterns use
  `class-variance-authority`. Don't add a third utility for the same
  job.
- **Theming**: dark only. Don't write `dark:` Tailwind variants —
  the dark palette is the _only_ palette, expressed via plain
  utilities (`bg-neutral-950`, `text-neutral-100`, etc.) sourced
  from the Tailwind tokens in `app.css`. The `useAppearance`
  composable still exists but is not surfaced; do not add a theme
  toggle without an ADR from `lead-dev`.
- Spacing scale: stick to Tailwind defaults. Arbitrary values only
  when the design genuinely demands it.

---

## 11. Frontend design directive

When building any UI, commit to a specific aesthetic before writing
code. Generic = failure.

### Hard bans

- Fonts: Inter, Roboto, Arial, Space Grotesk, system-ui as the
  _primary_ choice. Pair a distinctive display font with a refined
  body font from Google Fonts or Fontshare.
- Colors: purple-to-blue gradients on white. Tailwind defaults
  shipped as-is (`slate-900`, `indigo-600`, `gray-50`). "Trust blue"
  `#3B82F6` as a primary.
- Layouts: centered hero (headline + subheading + primary/secondary
  buttons), 3-column feature grid with circular icons, "trusted by"
  logo strip, glassmorphism cards on gradient backgrounds.
- Shapes: default `rounded-lg` / `rounded-xl` on every surface. Pick
  a corner radius language (sharp, soft, mixed) and stick to it.
- Shadows: stock `shadow-md` / `shadow-lg`. Design shadows
  intentionally or omit them.

### Required choices (make these explicitly, before coding)

1. **Aesthetic direction** — name one: editorial, brutalist,
   swiss/refined, retro-futuristic, organic, maximalist, art deco,
   industrial, etc. State it in a comment at the top of the file.
   This project leans into the operator-card / dossier aesthetic of
   the game itself — pick something that complements it, not
   generic SaaS.
2. **Type pairing** — display font + body font, with a clear
   hierarchy and at least one unexpected choice.
3. **Color system** — one dominant color (used heavily), one or two
   sharp accents, intentional neutrals. No evenly-distributed
   palettes.
4. **One memorable element** — the thing someone screenshots.
   Asymmetry, an oversized typographic moment, a custom cursor, a
   grain overlay, a diagonal section, a drawn illustration, an
   unusual scroll behavior. Pick one and execute it well.
5. **Motion budget** — one orchestrated entrance (staggered reveals
   via `animation-delay` or `tw-animate-css`) beats scattered
   micro-interactions. Use CSS where possible.

### Texture and depth

Backgrounds should not default to solid white or solid black. Reach
for: noise/grain overlays, subtle gradient meshes, geometric
patterns, layered transparencies, dotted/lined textures. Pick what
fits the aesthetic.

### Match complexity to vision

Minimal designs need precision in spacing, type scale, and alignment
— not blandness. Maximalist designs need real density and effects,
not just more elements. Both are valid; half-committed is not.

If a design could plausibly belong to any SaaS company, it's wrong.
Restart with a stronger aesthetic stance.

---

## 12. Testing

### 12.1 TDD with Pest

- **Failing test first.**
- **AAA**: Arrange, Act, Assert — visually separated by blank lines.
- **One assertion per test** where practical.
- Test names use Pest's `it(...)` form:
  `it('renders the login button when signed out', …)`.
- Feature tests in `tests/Feature/`, unit tests in `tests/Unit/`.

### 12.2 Manual verification

Even with tests, every change is manually verified:

- Golden path
- At least one edge case
- Both roles defined for the project (§6): `admin` and `guest`

State what you verified in your handoff message.

---

## 13. Definition of Done

A change is done when **all** of the following hold:

- [ ] Each touched file ≤ 250 lines (or split into a folder per §3).
- [ ] Lines ≤ 80 characters (≤ 70 in documentation examples).
- [ ] Styling stays inline; no new CSS abstractions; no new
      dependency added without justification.
- [ ] UI language verified — English, US conventions, no leakage.
- [ ] Keyboard-navigable; every input has a `<label for>`; `aria-*`
      where needed.
- [ ] WCAG AA contrast.
- [ ] Server-side input validated via FormRequest; client-side
      checks are UX only.
- [ ] No credentials or secrets committed; `.env` untouched.
- [ ] Manual verification: golden path + edge case + both roles
      (`admin` + `guest`) (or red→green automated test).
- [ ] §11 Frontend design directive respected on UI changes.
- [ ] Reviewed by `code-reviewer` before merge.

---

## 14. Agents

Six agents collaborate on this repo. Pick the right one; create it
if missing.

**Model requirement.** All agents — and the main session — run on
`claude-sonnet-4-6`. Each agent file declares a `model:` line in
its frontmatter; keep them in sync.

### `lead-dev` _(read-only)_

Architecture decisions, task decomposition, trade-off analysis,
cross-layer coordination (Vue ↔ Inertia ↔ Laravel ↔ DB). Produces
ADRs and delegation plans. **Does not write code.**

### `frontend-dev`

Inertia pages, Vue components, composables, UI strings,
accessibility.

### `backend-dev`

Laravel controllers, FormRequests, middleware, console commands,
server-side validation.

### `database-engineer`

Eloquent models and relationships, schema migrations, seeders,
factories.

### `qa-tester`

Pest tests (TDD), manual QA plans, regression checklists,
accessibility passes.

### `code-reviewer` _(read-only)_

Reviews pending diffs / PRs before merge.

---

## 15. Quick reference for new contributors

1. Install latest stable versions of the stack — don't pin to old
   majors. `composer install && npm install`.
2. Copy `.env.example` to `.env`, run `php artisan key:generate`,
   set the local MySQL credentials (`DB_CONNECTION=mysql`,
   `DB_HOST=localhost`, `DB_PORT=3306`, `DB_DATABASE=siege_x_guide`,
   plus `DB_USERNAME` / `DB_PASSWORD`), then `php artisan migrate`.
3. `composer dev` runs server + queue + logs + Vite together.
4. Read §16 (Behavioral guidelines) — it governs everything below.
5. Read §9 (UI/UX) before writing a single user-facing string.
6. When in doubt about architecture, ask `lead-dev` rather than
   guessing.
7. Ship through `code-reviewer`.

---

## 16. Behavioral guidelines

Adapted from
<https://github.com/forrestchang/andrej-karpathy-skills/blob/main/CLAUDE.md>.

**Tradeoff:** these guidelines bias toward caution over speed. For
trivial tasks, use judgment.

### 16.1 Think Before Coding

**Don't assume. Don't hide confusion. Surface tradeoffs.**

Before implementing:

- State your assumptions explicitly. If uncertain, ask.
- If multiple interpretations exist, present them — don't pick
  silently.
- If a simpler approach exists, say so. Push back when warranted.
- If something is unclear, stop. Name what's confusing. Ask.

### 16.2 Simplicity First

**Minimum code that solves the problem. Nothing speculative.**

- No features beyond what was asked.
- No abstractions for single-use code.
- No "flexibility" or "configurability" that wasn't requested.
- No error handling for impossible scenarios.
- If you write 200 lines and it could be 50, rewrite it.

Ask yourself: "Would a senior engineer say this is overcomplicated?"
If yes, simplify.

### 16.3 Surgical Changes

**Touch only what you must. Clean up only your own mess.**

When editing existing code:

- Don't "improve" adjacent code, comments, or formatting.
- Don't refactor things that aren't broken.
- Match existing style, even if you'd do it differently.
- If you notice unrelated dead code, mention it — don't delete it.

When your changes create orphans:

- Remove imports/variables/functions that YOUR changes made unused.
- Don't remove pre-existing dead code unless asked.

The test: every changed line should trace directly to the user's
request.

### 16.4 Goal-Driven Execution

**Define success criteria. Loop until verified.**

Transform tasks into verifiable goals:

- "Add validation" → "Write tests for invalid inputs, then make
  them pass"
- "Fix the bug" → "Write a test that reproduces it, then make it
  pass"
- "Refactor X" → "Ensure tests pass before and after"

For multi-step tasks, state a brief plan:

```
1. [Step] → verify: [check]
2. [Step] → verify: [check]
3. [Step] → verify: [check]
```

Strong success criteria let you loop independently. Weak criteria
("make it work") require constant clarification.

### 16.5 Clean Code — what we take, what we leave

Reference: Robert C. Martin, _Clean Code: A Handbook of Agile
Software Craftsmanship_. We adopt a curated subset. Where Clean
Code conflicts with **§16.2 Simplicity First** or **§16.3 Surgical
Changes**, those rules win — do not extract, layer, or generalize
beyond what the task demands.

**Adopted:**

- Meaningful, intention-revealing names. No `tmp`, `data2`, `arr`,
  `obj`. Use the domain word (`operator`, `squad`, `gadget`).
- Functions do one thing at one level of abstraction.
- Comments explain _why_, not _what_. If a comment paraphrases the
  code, rename or restructure — but only if the touch is small
  (§16.3).
- DRY when duplication is **semantic**. Coincidental duplication
  (two things that look alike but mean different things) stays
  duplicated.

**Skipped:**

- Aggressive method extraction. Don't pull a 3-line block into its
  own function "for clarity".
- Class explosions (Service / Repository / Mapper / Factory) for
  app code that doesn't need them.
- Premature interfaces. No `IFoo` until there's a real second
  implementation.
- Renaming widely-used identifiers for purely aesthetic reasons.
- "Clean rewrites" of code that works.

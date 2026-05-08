# AGENTS.md

Guidance for any agent (Claude Code or human) working in this repository.
Read this **before** writing or reviewing code.

> **How to use this template.** This file is a project-agnostic baseline.
> Fill in the `<TODO: …>` blocks with project-specific facts (stack, auth,
> roles, language, audience, persistence layer, etc.) and delete sections
> that don't apply. Sections §12 (Frontend design discipline), §14
> (Definition of Done), §15 (Agents), §17 (Behavioral guidelines) are
> intended to stay roughly as-is on every project.

---

## 1. Project at a glance

<TODO: one-paragraph description of the product. Who uses it, what it
does, where it runs.>

Key facts that drive every decision below:

- **Domain / audience**: <TODO: who is the user, what's their tech literacy>
- **UI language(s)**: <TODO: e.g. English only / French only / bilingual>
- **Auth**: <TODO: e.g. OIDC via X, email+password, SSO with Y>
- **Roles**: <TODO: list the roles, e.g. `admin`, `user`>
- **Persistence**: <TODO: e.g. Postgres via Supabase, MySQL, DynamoDB>
- **Audit**: every database-modifying action is logged (see §8).
- **Theming**: light + dark, toggleable, both WCAG AA.
- **Notifications** (if any): <TODO: e.g. Resend, SES, none>

---

## 2. Tech stack

Always install the **latest stable** version of each dependency. Do not
pin to outdated majors without a written reason.

<TODO: enumerate the stack. Example skeleton below — replace with what
the project actually uses.>

- Language / runtime: <TODO>
- Frontend framework: <TODO>
- Build tool: <TODO>
- Styling: <TODO>
- State / data libraries: <TODO>
- Backend / serverless: <TODO>
- Database / ORM: <TODO>
- Test runner: <TODO>
- Icons: <TODO>

No additional UI / state libraries unless justified in an ADR by
`lead-dev`. Utility helpers that exist in three places or fewer should
not be promoted to a dependency — write the four-line helper inline.

---

## 3. File organization

<TODO: replace the tree below with the project's actual structure.>

```
src/
  routes/          # route-level components, one folder per route
  components/      # reusable, presentational
  features/        # feature folders
  lib/             # client, helpers, formatters
  hooks/
  types/
  styles/
<persistence-layer>/
  migrations/
  functions/       # serverless / edge functions
```

### Per-file rules

- **~250 lines soft cap.** When a component or module grows past that,
  **split into a folder** and colocate sub-components / sub-modules:

  ```
  Thing/
    index.<ext>           # composes the rest, default export
    ThingHeader.<ext>
    ThingRow.<ext>
    ThingFilters.<ext>
    types.<ext>           # if needed
  ```

- **~80 characters per line.** Avoid lines longer than 80 characters —
  many terminals and tools handle them poorly. Documentation examples
  should be shorter, generally no more than 70 characters.
- One responsibility per file. If the file mixes data fetching,
  formatting, and rendering, it's probably three files.
- No barrel files re-exporting everything; import from the explicit
  path.

---

## 4. Routing

- All routes declared in a single, easy-to-grep file for visibility.
- **Protected routes** wrap children with an auth + role gate. The gate
  reads the session and the user's role; if either fails, redirect.
- Client-side role checks are **UX only** — server-side authorization
  (RLS, policy engine, middleware, …) is the source of truth.

---

## 5. Authentication

<TODO: describe the auth flow. Replace the bullets below.>

- On app boot: read the session, refresh if needed, hydrate user + role
  into a context.
- Sign-out clears the session **and** the local context. Redirect to the
  login route.
- Never store tokens in `localStorage` manually — let the SDK handle it
  if it can.

---

## 6. Roles & permissions

Default roles: <TODO: e.g. `admin` | `user`>. May be extended on a
per-project basis. When extended, update §13.2 and §14 to enumerate the
project's actual role set.

- Stored in a `profiles` (or equivalent) table keyed by the auth user
  id.
- **Server-side authorization on every resource.** Default-deny.
- Client reads the role once after sign-in, caches it in context, and
  uses it to gate UI (hide buttons, disable actions). The server
  enforces.
- Role changes are an audited admin action (see §8).

---

## 7. Database & migrations

- All schema changes go through **migrations checked into the repo**.
  No clicking in the dashboard.
- **Authorization on every table / collection.** No exceptions. A
  migration that creates a table must also create its policies in the
  same migration.
- Privileged keys (service role, admin DB credentials) are used **only**
  in server-side code. They must never appear in the client bundle or
  in any client-visible env var.
- Migrations are owned by `database-engineer`.
- TypeScript types for the schema are **generated**, not hand-written,
  whenever the stack supports it. Document the generation command in
  the README and run it after every applied migration.

---

## 8. Audit logging

Every `INSERT` / `UPDATE` / `DELETE` (or equivalent) on user-data tables
is recorded.

- Table `audit_log` (or equivalent) with at least: `id`, `actor_id`,
  `action` (`insert|update|delete`), `table_name`, `row_id`, `before`
  (json), `after` (json), `occurred_at` (timestamp, default `now()`).
- Preferred implementation: a database trigger calling a shared
  `log_change()` function. Triggers run with the user's auth id as
  `actor_id`.
- For changes that go through a privileged server-side function (e.g.
  role assignment), the function writes the audit row explicitly with
  the *real* actor, not the service identity.
- Read access: `admin` only, enforced via authorization.
- Audit rows are **append-only**. No `UPDATE` or `DELETE` policies on
  `audit_log` for any role.

---

## 9. Security policy

- No privileged keys in the client. Ever.
- Only safe values may live in client-visible env vars (public URL,
  anon / publishable key). Treat anything client-visible as public.
- Validate and authorize every server-side input, even if the client
  already did.
- HTTPS only.
- Sanitize anything rendered as HTML. Prefer plain text rendering by
  default.
- No PII in client logs, analytics, or error trackers.
- Sign-in/out, role changes, and admin actions are all audited.

---

## 10. UI / UX

### 10.1 Language

<TODO: state the project's language(s) and typography rules. Examples:
"English only, US conventions" or "French only, with the rules below".>

If the project uses a single non-English language, write down the
typography rules explicitly (accents, spacing, decimal separator, date
format, vocabulary choices) so that nothing leaks in another language.
Centralize all user-facing strings in one location (e.g.
`src/lib/i18n/<lang>.ts`) so corrections happen in one place.

### 10.2 Audience

<TODO: describe the audience and the UX consequences. Replace the
generic baseline below if the project's audience demands more or less.>

Sensible baseline:

- Plain language. No jargon. Spell out acronyms on first use.
- Base font ≥ 16 px, body line-height ≥ 1.5.
- Click/tap targets ≥ 44×44 px.
- Buttons have **icon + text label** — never icon-only for actions.
- Confirm destructive actions with explicit wording.
- Error messages: what went wrong + how to fix it, in plain language.
  No error codes alone.
- Generous whitespace, clear visual hierarchy, one primary action per
  screen.

### 10.3 Accessibility — WCAG 2.1 AA

- **Keyboard**: every interactive surface reachable via `Tab`,
  activatable via `Enter` / `Space`. Logical tab order.
- `<label htmlFor="id">` on every input. **Never** use placeholder as
  label.
- `aria-*` only where role alone doesn't convey meaning.
- Modals: `role="dialog"` + `aria-modal="true"` + focus trap.
- Visible focus ring at all times. Don't suppress `:focus-visible`.
- Contrast ≥ 4.5:1 for text, ≥ 3:1 for UI components, in **both** light
  and dark modes.
- Never convey information by color alone (pair with icon, text, or
  pattern).

---

## 11. Styling conventions

<TODO: adapt to the project's styling system. The rules below assume
Tailwind; adjust if you use CSS Modules, vanilla-extract, styled
components, etc.>

- Classes / styles **inline on the component**. Avoid clever
  abstractions (`@apply`, deep nesting, CSS-in-JS layers) until they
  earn their keep.
- Conditional classes: tiny local helper or template literals. Don't
  add a utility library for a one-liner.
- **Dark mode**: every visual that defines a color/border/background
  has a `dark:` (or equivalent) counterpart. Verify both modes before
  declaring done.
- Spacing scale: stick to defaults. Arbitrary values only when the
  design genuinely demands it.

### Dark mode toggle

- Implement a single `ThemeToggle` component in the app shell.
- On first load: respect `prefers-color-scheme`; afterwards, persist
  user choice in `localStorage` (key `theme`, value `'light' | 'dark'`).
- Toggle is keyboard-operable and has an `aria-pressed` state.

---

## 12. Frontend design directive

When building any UI, commit to a specific aesthetic before writing
code. Generic = failure.

### Hard bans

- Fonts: Inter, Roboto, Arial, Space Grotesk, system-ui as the *primary*
  choice. Pair a distinctive display font with a refined body font from
  Google Fonts or Fontshare.
- Colors: purple-to-blue gradients on white. Tailwind defaults shipped
  as-is (`slate-900`, `indigo-600`, `gray-50`). "Trust blue"
  `#3B82F6` as a primary.
- Layouts: centered hero (headline + subheading + primary/secondary
  buttons), 3-column feature grid with circular icons, "trusted by"
  logo strip, glassmorphism cards on gradient backgrounds.
- Shapes: default `rounded-lg` / `rounded-xl` on every surface. Pick a
  corner radius language (sharp, soft, mixed) and stick to it.
- Shadows: stock `shadow-md` / `shadow-lg`. Design shadows
  intentionally or omit them.

### Required choices (make these explicitly, before coding)

1. **Aesthetic direction** — name one: editorial, brutalist,
   swiss/refined, retro-futuristic, organic, maximalist, art deco,
   industrial, etc. State it in a comment at the top of the file.
2. **Type pairing** — display font + body font, with a clear hierarchy
   and at least one unexpected choice.
3. **Color system** — one dominant color (used heavily), one or two
   sharp accents, intentional neutrals. No evenly-distributed palettes.
4. **One memorable element** — the thing someone screenshots.
   Asymmetry, an oversized typographic moment, a custom cursor, a
   grain overlay, a diagonal section, a drawn illustration, an unusual
   scroll behavior. Pick one and execute it well.
5. **Motion budget** — one orchestrated entrance (staggered reveals
   via `animation-delay`) beats scattered micro-interactions. Use CSS
   where possible.

### Texture and depth

Backgrounds should not default to solid white or solid black. Reach
for: noise/grain overlays, subtle gradient meshes, geometric patterns,
layered transparencies, dotted/lined textures. Pick what fits the
aesthetic.

### Match complexity to vision

Minimal designs need precision in spacing, type scale, and alignment —
not blandness. Maximalist designs need real density and effects, not
just more elements. Both are valid; half-committed is not.

If a design could plausibly belong to any SaaS company, it's wrong.
Restart with a stronger aesthetic stance.

---

## 13. Testing

### 13.1 TDD with the project's test runner

- **Failing test first.**
- **AAA**: Arrange, Act, Assert — visually separated.
- **One assertion per test.**
- Test names start with `should_…`, e.g.
  `should_render_login_button_when_signed_out`.
- Co-locate tests next to source: `Foo.<ext>` ↔ `Foo.test.<ext>`.

### 13.2 Manual verification

Even with tests, every change is manually verified:

- Golden path
- At least one edge case
- Every role defined for the project (see §6)
- Both themes: light and dark

State what you verified in your handoff message.

---

## 14. Definition of Done

A change is done when **all** of the following hold:

- [ ] Each touched file ≤ 250 lines (or split into a folder per §3).
- [ ] Lines ≤ 80 characters (≤ 70 in documentation examples).
- [ ] Styling stays inline; no new CSS abstractions; no new dependency
      added without justification.
- [ ] Every visual has a dark-mode counterpart.
- [ ] UI language verified — correct typography, no leakage of other
      languages.
- [ ] Keyboard-navigable; every input has a `<label htmlFor>`; `aria-*`
      where needed.
- [ ] WCAG AA contrast in both themes.
- [ ] Any DB-modifying action is captured by the audit pipeline.
- [ ] No privileged keys reachable from the client.
- [ ] Manual verification: golden path + edge case + every role per §6
      + both themes (or red→green automated test).
- [ ] §12 Frontend design directive respected on UI changes.
- [ ] Reviewed by `code-reviewer` before merge.

---

## 15. Agents

Six agents collaborate on this repo. Pick the right one; create it if
missing.

**Model requirement.** All agents — and the main session — must run on
the project's chosen frontier model. Each agent file declares a
`model:` line in its frontmatter; keep them in sync.

### `lead-dev` *(read-only)*
Architecture decisions, task decomposition, trade-off analysis,
cross-layer coordination (frontend ↔ server ↔ database). Produces ADRs
and delegation plans. **Does not write code.**

### `frontend-dev`
Routes, components, hooks, UI strings, dark mode, accessibility.

### `backend-dev`
Server-side functions, privileged-key work, one-off scripts,
server-side validation.

### `database-engineer`
Schema migrations, authorization policies, audit triggers.

### `qa-tester`
Automated tests (TDD), manual QA plans, regression checklists,
accessibility passes.

### `code-reviewer` *(read-only)*
Reviews pending diffs / PRs before merge.

---

## 16. Quick reference for new contributors

1. Install latest stable versions of the stack — don't pin to old
   majors.
2. Read §17 (Behavioral guidelines) — it governs everything below.
3. Read §10 (UI/UX) before writing a single user-facing string.
4. Read §8 (audit) before writing a migration or server-side function.
5. When in doubt about architecture, ask `lead-dev` rather than
   guessing.
6. Ship through `code-reviewer`.

---

## 17. Behavioral guidelines

Adapted from
<https://github.com/forrestchang/andrej-karpathy-skills/blob/main/CLAUDE.md>.

**Tradeoff:** these guidelines bias toward caution over speed. For
trivial tasks, use judgment.

### 17.1 Think Before Coding

**Don't assume. Don't hide confusion. Surface tradeoffs.**

Before implementing:

- State your assumptions explicitly. If uncertain, ask.
- If multiple interpretations exist, present them — don't pick
  silently.
- If a simpler approach exists, say so. Push back when warranted.
- If something is unclear, stop. Name what's confusing. Ask.

### 17.2 Simplicity First

**Minimum code that solves the problem. Nothing speculative.**

- No features beyond what was asked.
- No abstractions for single-use code.
- No "flexibility" or "configurability" that wasn't requested.
- No error handling for impossible scenarios.
- If you write 200 lines and it could be 50, rewrite it.

Ask yourself: "Would a senior engineer say this is overcomplicated?"
If yes, simplify.

### 17.3 Surgical Changes

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

### 17.4 Goal-Driven Execution

**Define success criteria. Loop until verified.**

Transform tasks into verifiable goals:

- "Add validation" → "Write tests for invalid inputs, then make them
  pass"
- "Fix the bug" → "Write a test that reproduces it, then make it pass"
- "Refactor X" → "Ensure tests pass before and after"

For multi-step tasks, state a brief plan:

```
1. [Step] → verify: [check]
2. [Step] → verify: [check]
3. [Step] → verify: [check]
```

Strong success criteria let you loop independently. Weak criteria
("make it work") require constant clarification.

### 17.5 Clean Code — what we take, what we leave

Reference: Robert C. Martin, _Clean Code: A Handbook of Agile Software
Craftsmanship_. We adopt a curated subset. Where Clean Code conflicts
with **§17.2 Simplicity First** or **§17.3 Surgical Changes**, those
rules win — do not extract, layer, or generalize beyond what the task
demands.

**Adopted:**

- Meaningful, intention-revealing names. No `tmp`, `data2`, `arr`,
  `obj`. Use the domain word.
- Functions do one thing at one level of abstraction.
- Comments explain *why*, not *what*. If a comment paraphrases the
  code, rename or restructure — but only if the touch is small (§17.3).
- DRY when duplication is **semantic**. Coincidental duplication (two
  things that look alike but mean different things) stays duplicated.

**Skipped:**

- Aggressive method extraction. Don't pull a 3-line block into its own
  function "for clarity".
- Class explosions (Service / Repository / Mapper / Factory) for app
  code that doesn't need them.
- Premature interfaces. No `IFoo` until there's a real second
  implementation.
- Renaming widely-used identifiers for purely aesthetic reasons.
- "Clean rewrites" of code that works.

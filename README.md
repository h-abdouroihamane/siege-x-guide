# Siege X Guide

A community guide for **Rainbow Six Siege X**. Browse operators,
their gadgets and roles, the squads they belong to, and the queer
representation across the roster.

Live site: <https://siege-x-guide.alsagone.ovh>

---

## What this is

Public visitors get a read-only catalog of operators they can
browse and filter. A single signed-in admin can add and edit
entries. The visual reference is the official R6 Siege operators
page on Ubisoft.com — a tactical-dossier aesthetic, dark only.

The site is intentionally narrow in scope: no comments, no
accounts, no notifications. Just an opinionated, well-curated
operator catalog.

---

## Tech stack

- **Backend**: Laravel 12, PHP 8.2+
- **Server↔client bridge**: Inertia.js
- **Frontend**: Vue 3.5 + TypeScript 5
- **Build**: Vite 6
- **Styling**: Tailwind CSS v4
- **Database**: MySQL 8
- **Tests**: Pest 3

Full stack details and version policy live in
[AGENTS.md §2](AGENTS.md).

---

## Getting started

Two supported paths:

- **Native** (regular Linux/macOS): see
  [AGENTS.md §15](AGENTS.md). PHP, Composer, Node, and a local
  MySQL — `composer dev` runs the full dev stack.
- **Containerized** (Bazzite or any Fedora atomic distro): see
  [DEVELOPMENT.md](DEVELOPMENT.md). Laravel Sail on rootful
  Podman, with the workarounds you'll need for SELinux and the
  rootless-namespace traps.

If you're on standard Linux/macOS and just want to run the site,
the native path is faster and has fewer moving parts.

---

## Project layout

```
app/                   # Laravel: controllers, models, requests, resources
database/              # migrations, factories, seeders
resources/js/
    pages/             # Inertia page components, one per route
    components/        # reusable Vue components
    composables/       # Vue composables
    types/             # TypeScript domain types
routes/                # one file per resource group
tests/
    Feature/           # HTTP + integration tests
    Unit/              # model and pure-logic tests
```

Per-file rules (~250 line cap, no barrel files, etc.) are
documented in [AGENTS.md §3](AGENTS.md).

---

## Testing

Pest 3 powers both feature and unit suites. Run the lot:

```
php artisan test
# or via Sail:
sail test
```

The TDD expectations (failing test first, AAA structure, one
assertion per test) are in [AGENTS.md §12](AGENTS.md).

---

## Contributing

Every change goes through the rules in [AGENTS.md](AGENTS.md).
Read at minimum:

- §3 — file organization and the 250-line cap
- §9 — UI/UX baseline, including WCAG AA requirements
- §11 — frontend design directive (the dossier aesthetic, the
  hard bans, the required choices)
- §13 — the Definition of Done checklist
- §16 — behavioral guidelines (simplicity, surgical changes)

Six specialist agents collaborate on this repo — `lead-dev`,
`frontend-dev`, `backend-dev`, `database-engineer`, `qa-tester`,
`code-reviewer`. See [AGENTS.md §14](AGENTS.md) for who owns
what.

---

## License

MIT.

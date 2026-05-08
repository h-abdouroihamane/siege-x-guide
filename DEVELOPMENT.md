# DEVELOPMENT.md

How to run Siege X Guide locally inside containers via Laravel Sail
on **Bazzite** (or any Fedora-based atomic distro: Silverblue,
Bluefin, Kinoite). The native-MySQL path is in
[AGENTS.md §15](AGENTS.md) — pick that one if you're on a regular
Linux/macOS install. This file exists because rootless Podman on
atomic Fedora needs a few non-obvious tweaks before Sail will run.

---

## Why a custom guide

Sail was written for Docker. Bazzite ships Podman, and Podman's
default rootless mode collides with Sail's `start-container`
script (it expects real `root` inside the container, not a
namespace-mapped UID). We work around it by using **rootful
Podman** with a Docker-compatible socket, plus two `docker-compose.yml`
tweaks for SELinux.

If you're new to the project and want the simplest possible setup,
go native (AGENTS.md §15). This file is for people who specifically
want containerized dev on an atomic Fedora host.

---

## Prerequisites

- Bazzite (or Fedora Silverblue / Bluefin / Kinoite) on a recent
  release. Podman 4.x or 5.x ships in the base image.
- The `podman-docker` shim package layered onto the host:
    ```
    rpm-ostree install podman-docker
    systemctl reboot
    ```
- A working `docker-compose` binary (Sail invokes
  `docker compose` — the shim plus podman-compose handles this on
  Bazzite). Verify with `docker compose version`.

---

## One-time setup

### 1. Enable the rootful Podman socket

```
sudo systemctl enable --now podman.socket
```

This exposes a Docker-compatible socket at
`/run/podman/podman.sock`, owned by root.

If you previously enabled the rootless user socket, disable it so
`DOCKER_HOST` resolution is unambiguous:

```
systemctl --user disable --now podman.socket
```

### 2. Point `DOCKER_HOST` at the rootful socket

Add to `~/.bashrc` (or `~/.zshrc`):

```
export DOCKER_HOST="unix:///run/podman/podman.sock"
```

Then `source ~/.bashrc`.

### 3. Add a sudo-prefixed `sail` alias

The rootful socket is root-owned, so every `sail` command needs
`sudo`. The `-E` preserves `DOCKER_HOST` (otherwise sudo strips
it and Podman silently falls back to the rootless socket):

```
alias sail='sudo -E vendor/bin/sail'
```

### 4. Install Sail in the project

From the project root:

```
composer require laravel/sail --dev
php artisan sail:install
```

When prompted, pick **mysql** only (no Redis / Mailpit / etc. —
the project doesn't use them).

### 5. Patch `docker-compose.yml`

Sail's generated compose file assumes Docker. Two edits in the
`laravel.test:` service:

```yaml
laravel.test:
    # ... existing keys ...
    volumes:
        - '.:/var/www/html:z' # add :z for SELinux relabel
```

The `:z` flag tells Podman to relabel the bind mount so SELinux
lets the container read your project files. Without it the
container starts but reports
`Could not open input file: artisan`.

**Do not** add `userns_mode: 'keep-id'` — that's a rootless-only
feature and is unnecessary (and counterproductive) under rootful
Podman.

### 6. Configure `.env`

```
APP_URL=http://localhost:8080
APP_PORT=8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=siege_x_guide
DB_USERNAME=sail
DB_PASSWORD=password

WWWUSER=1000
WWWGROUP=1000
```

Notes:

- **`APP_PORT=8080`** — rootless and rootful Podman both refuse
  to bind privileged ports (< 1024) without extra sysctl
  permissions. Picking 8080 dodges the issue entirely.
- **`APP_URL=http://localhost:8080`** must match `APP_PORT`, or
  Laravel-generated URLs (assets, redirects, mailables) will
  point at the wrong port.
- **`DB_HOST=mysql`** — the service name from
  `docker-compose.yml`, not `localhost`. Inside the
  `laravel.test` container, `mysql` resolves to the database
  container on the Compose network. AGENTS.md §15's
  `DB_HOST=localhost` is for the native path.
- **`WWWUSER` / `WWWGROUP`** must equal `id -u` / `id -g` on
  the host so the `sail` user inside the container shares your
  UID. Sail's installer sets this; just confirm.

### 7. First boot

```
sail build --no-cache
sail up -d
sail artisan key:generate
```

MySQL takes 20–60 seconds to initialize a fresh data volume.
Wait for "ready for connections" before migrating:

```
sail logs mysql | tail -20
```

Then:

```
sail artisan migrate
sail artisan db:seed
sail npm install
sail npm run dev
```

Site at `http://localhost:8080`.

---

## Daily workflow

```
sail up -d           # start everything in the background
sail logs -f         # follow combined logs
sail artisan ...     # run any artisan command
sail test            # run the Pest suite
sail npm run dev     # Vite dev server (if not already running)
sail mysql           # mysql shell against the running DB
sail down            # stop and remove containers (keeps volumes)
sail down -v         # also drop the mysql data volume
```

---

## Troubleshooting

### `Error response from daemon: rootlessport cannot expose privileged port 80`

You're still on the rootless socket. Either set `APP_PORT=8080`
in `.env` (recommended) or follow Step 1 above to switch to the
rootful socket.

### `Could not open input file: artisan`

The bind mount of your project into `/var/www/html` is being
blocked by SELinux. Add `:z` to the volume mount in
`docker-compose.yml` (Step 5 above) and rebuild:

```
sail down
sail build --no-cache
sail up -d
```

### `Error: Can't drop privilege as nonroot user` in `laravel.test` logs

Sail's start-container script tried to run as root and failed.
You're on rootless Podman. Switch to the rootful socket (Step 1)
and remove `userns_mode: 'keep-id'` from `docker-compose.yml` if
you added it earlier.

### `SQLSTATE[HY000] [2002] No such file or directory`

PHP is trying to reach MySQL via a Unix socket. `DB_HOST` is
missing or set to `localhost`/`127.0.0.1` in `.env`. Set
`DB_HOST=mysql` and re-run.

### `SQLSTATE[HY000] [2002] Connection refused`

PHP found the right host but MySQL isn't listening yet. On a
fresh volume MySQL takes up to a minute to initialize. Wait for
"ready for connections" in `sail logs mysql`, then retry:

```
sail up -d && sleep 30 && sail artisan migrate
```

If you previously booted MySQL with different credentials, the
volume baked them in. Reset:

```
sail down -v       # drops the mysql volume
sail up -d
sail artisan migrate
```

### Containers come up but immediately exit

Check `sail logs laravel.test`. If you see `usermod: UID '1000'
already exists` or `mkdir: cannot create directory '/.composer'`,
you're on rootless Podman — switch to rootful (Step 1).

### `>>>> Executing external compose provider …` is noisy

Cosmetic, not an error. To silence:

```
sudo touch /etc/containers/nodocker
```

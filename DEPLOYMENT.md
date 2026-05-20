# Deployment — siege-x-guide on OVH (Docker)

Target: **OVH VPS/Dedicated** (root SSH, Debian/Ubuntu) · **2 vCore / 2 GB RAM / 40 GB disk**
Edge: **host nginx + Let's Encrypt** reverse-proxying the container
Domain: **siege-x-guide.alsagone.ovh**

The app is a read-only public guide. The stack is **a single container**
(nginx + php-fpm) with a **seeded SQLite database baked into the image
at build time**. Content is seeder-only and fully reproducible from git,
so every deploy ships a freshly seeded DB — there is no separate DB
service, no persistent volume, no migrate/seed logic at runtime, and no
backup story (the seeders in git are the backup).

The production `.env` fetched from the VPS is bind-mounted read-only
into the container. `APP_ENV`/`APP_DEBUG`/`DB_CONNECTION` are forced via
Compose env (Laravel immutable dotenv: real env vars win over `.env`),
so the app always runs production and on SQLite regardless of legacy
values in `.env`.

---

## 0. Reinstalling the OVH host (only if needed)

Skip this if the VPS already runs a supported OS. Do it if the host is on
an **end-of-life Ubuntu** (e.g. 24.10 "Oracular" — symptom: `apt update`
returns `404 ... no longer has a Release file`, Docker's script aborts with
an EOL warning). Docker does not publish repos for EOL releases, and an
unpatched internet-facing host is a liability, so rebuild on an LTS.

Nothing of value lives on the box: code is in GitLab, content is
seeder-only, and the production `.env` lives on your laptop. The reinstall
**does** take the old site down until the container is back up.

1. **OVH Control Panel → Bare Metal Cloud → VPS →** select the VPS.
2. Actions menu → **"Reinstall my VPS"**.
3. Distribution: **Ubuntu 24.04 LTS** (or Debian 12 — steps below are
   identical). No extra control-panel "soft".
4. **Add your SSH public key** in the dialog (preferred over a root
   password). Create one first if needed: `ssh-keygen -t ed25519`.
5. Confirm. ~5–15 min; OVH emails the details. **The IP is unchanged**, so
   the `siege-x-guide` DNS record stays valid.

Reconnect — the server's SSH host key changed, so clear the stale one:

```bash
ssh-keygen -R <vps-ip>
ssh-keygen -R siege-x-guide.alsagone.ovh
ssh <user>@<vps-ip>          # accept the new fingerprint
dig +short siege-x-guide.alsagone.ovh   # confirm it still points here
```

In **GitLab → Settings → Repository → Deploy keys**, delete the old
`ovh-deploy` key (it no longer exists on the rebuilt box); you create a
fresh one in step 3.

Then continue from step 1 below. On a fresh LTS the earlier failures don't
recur: `apt update` works, the Docker script completes (no EOL prompt —
**don't Ctrl+C it**), and there is no stale `ondrej/php` PPA (the
Docker-only deploy never needs host PHP — don't add it).

---

## 1. DNS (OVH Manager)

**Web Cloud → Domain names → `alsagone.ovh` → DNS zone**:

- **A** record: `siege-x-guide` → VPS IPv4
- (optional) **AAAA** → VPS IPv6

```bash
dig +short siege-x-guide.alsagone.ovh   # must return the VPS IP
```

---

## 2. Server prerequisites

```bash
sudo apt update && sudo apt -y upgrade

# Docker Engine + compose plugin
curl -fsSL https://get.docker.com | sudo sh
sudo systemctl enable --now docker
sudo usermod -aG docker $USER && newgrp docker
docker compose version

# Firewall (host ufw)
sudo apt -y install ufw
sudo ufw allow OpenSSH
sudo ufw allow 80,443/tcp
sudo ufw enable
```

Port **8080 is never exposed publicly** — the app binds `127.0.0.1:8080`
and the host nginx fronts it.

### 2a. Swap (required — 2 GB RAM is tight)

The image build (`npm ci` + `vite build`) needs headroom on 2 GB. Add
2 GB swap once:

```bash
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
free -h
```

---

## 3. Get the code (private GitLab repo)

```bash
ssh-keygen -t ed25519 -C "ovh-deploy" -f ~/.ssh/gitlab_deploy -N ""
cat ~/.ssh/gitlab_deploy.pub
```

Add the public key in **GitLab → project → Settings → Repository →
Deploy keys** (read-only). Then:

```bash
cat >> ~/.ssh/config <<'EOF'
Host gitlab.com
  IdentityFile ~/.ssh/gitlab_deploy
EOF

sudo mkdir -p /var/www && sudo chown $USER /var/www
git clone git@gitlab.com:alsagone/siege-x-guide.git \
  /var/www/siege-x-guide.alsagone.ovh
cd /var/www/siege-x-guide.alsagone.ovh
git checkout main
```

---

## 4. Install the production `.env`

The compose file mounts `./.env` into the container **read-only**. It
must exist **before** `docker compose up` — a bind mount to a missing
path makes Docker create a _directory_ called `.env` and the app breaks.

```bash
# from your machine:
scp .env user@<vps>:/var/www/siege-x-guide.alsagone.ovh/.env

cd /var/www/siege-x-guide.alsagone.ovh
chmod 600 .env
grep -E '^APP_KEY=base64:|^APP_URL=|^CACHE_STORE=|^QUEUE_CONNECTION=|^SESSION_DRIVER=' .env
```

`.env` **must** contain:

| Key                | Requirement                                                                                                               |
| ------------------ | ------------------------------------------------------------------------------------------------------------------------- |
| `APP_KEY`          | `base64:...` (mount is read-only — it can't be generated)                                                                 |
| `APP_URL`          | `https://siege-x-guide.alsagone.ovh`                                                                                      |
| `CACHE_STORE`      | `array` — Laravel 11 defaults to `database`; without this, cache writes hit the baked-in SQLite and reset on every deploy |
| `QUEUE_CONNECTION` | `sync` — same reason (default is `database`)                                                                              |
| `SESSION_DRIVER`   | `file` — same reason (default is `database`)                                                                              |

`APP_ENV`/`APP_DEBUG`/`DB_CONNECTION` are forced via Compose env vars
(`docker-compose.yml`), so they're optional in `.env`. `DB_*` values are
ignored entirely — the DB file lives inside the image. `.env` is
gitignored and `.dockerignore`-excluded; it never enters git or the
image.

---

## 5. Build & start

```bash
cd /var/www/siege-x-guide.alsagone.ovh
docker compose build              # composer → vite → migrate+seed → runtime (uses swap)
docker compose up -d
docker compose ps                 # app: running
docker compose logs -f app
curl -I http://127.0.0.1:8080     # HTTP/1.1 200
```

**Always `docker compose build` before `up`** — `siege-x-guide` is a
local build-only image (`pull_policy: build`); a plain `up` on a host
that has never built it would otherwise try to pull it from Docker Hub
and fail with `pull access denied`.

The migrate+seed step runs **inside the image build**, so the resulting
image is fully self-contained — no first-boot DB setup, no waiting on a
DB service.

If `docker compose build` is OOM-killed: confirm step 2a swap is active
and retry, or build elsewhere and ship the image:
`docker save siege-x-guide | ssh <vps> 'docker load'` then
`docker compose up -d`.

---

## 6. nginx reverse proxy + HTTPS

```bash
sudo apt -y install nginx certbot python3-certbot-nginx
sudo tee /etc/nginx/sites-available/siege-x-guide >/dev/null <<'EOF'
server {
    listen 80;
    listen [::]:80;
    server_name siege-x-guide.alsagone.ovh;

    location / {
        proxy_pass         http://127.0.0.1:8080;
        proxy_set_header   Host              $host;
        proxy_set_header   X-Real-IP         $remote_addr;
        proxy_set_header   X-Forwarded-For   $proxy_add_x_forwarded_for;
        proxy_set_header   X-Forwarded-Proto $scheme;
        proxy_http_version 1.1;
    }
}
EOF

sudo ln -s /etc/nginx/sites-available/siege-x-guide /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
sudo certbot --nginx -d siege-x-guide.alsagone.ovh --redirect \
  -m you@example.com --agree-tos
sudo systemctl status certbot.timer
```

Open `https://siege-x-guide.alsagone.ovh`.

---

## 7. Redeploy / update (`scripts/deploy.sh`)

Use the committed wrapper — run it on the VPS from the project dir:

```bash
cd /var/www/siege-x-guide.alsagone.ovh
./scripts/deploy.sh
```

It pulls `main` (`--ff-only`), `docker compose up -d --build`, prunes
old images, and health-checks `:8080`. Because the SQLite DB is baked
into the image, **every deploy ships fresh content** — there is no
separate `--seed` flow. Edit the seeders, push, run the script, done.

Brief blip while the new container starts. The deploy is atomic at the
image level: roll back by checking out an earlier commit and rebuilding.

---

## 8. Retire the old setup

Once the container serves traffic, remove the old non-Docker vhost /
PHP-FPM config and any cron, leaving only the reverse-proxy server
block from step 6. The old standalone MySQL on the host can be
stopped/removed — the app no longer uses a separate database service at
all.

---

## Troubleshooting

- **`could not find driver`** — the runtime image must have `pdo_sqlite`
  (the `webdevops/php-nginx` image bundles it; verify with
  `docker compose exec app php -m | grep pdo_sqlite`).
- **Migrate/seed failed during build** — `docker compose build` will
  surface the artisan error directly. Most often a seeder bug; reproduce
  locally with `php artisan migrate:fresh --seed`.
- **Stale content after deploy** — confirm the image actually rebuilt
  (`docker compose up -d --build`, not just `up -d`). The deploy script
  always rebuilds.

---

## Quick reference

| Item              | Value                                               |
| ----------------- | --------------------------------------------------- |
| Project path      | `/var/www/siege-x-guide.alsagone.ovh`               |
| Services          | `app` only (127.0.0.1:8080 → :80)                   |
| Public ports      | 80/443 via host nginx                               |
| Secrets           | `./.env` (bind-mounted read-only; only APP_KEY/URL) |
| DB                | SQLite, baked into image at build time              |
| Content refresh   | Edit seeders → `./scripts/deploy.sh`                |
| Logs              | `docker compose logs -f app`                        |
| Update            | `git pull && docker compose up -d --build`          |
| Build/runtime RAM | needs 2 GB swap (step 2a)                           |

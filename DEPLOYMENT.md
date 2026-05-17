# Deployment — siege-x-guide on OVH (Docker)

Target: **OVH VPS/Dedicated** (root SSH, Debian/Ubuntu) · **2 vCore / 2 GB RAM / 40 GB disk**
Edge: **host nginx + Let's Encrypt** reverse-proxying the container
Domain: **siege-x-guide.alsagone.ovh**

The app is a read-only public guide. The stack is two containers:

- **app** — nginx + php-fpm serving the built Inertia/Vue site.
- **db** — MySQL 8, data persisted in the `db-data` named volume.

Content is **seeder-only and fully reproducible**, so the database is
**seeded exactly once** (the entrypoint seeds only when it's empty) and
then **persists across restarts and redeploys**. The image is
self-contained (no git pull at runtime) and replaces the old
`scripts/deploy.sh`.

The production `.env` fetched from the VPS is bind-mounted read-only into
the app **and** is the file Compose reads to configure the MySQL service —
one file keeps both sides in agreement. `APP_ENV`/`APP_DEBUG` and the DB
connection are forced via Compose env (Laravel immutable dotenv: real env
vars win over `.env`), so the app always runs production and talks to the
`db` service regardless of legacy values in `.env`.

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

Port **8080 and MySQL are never exposed publicly** — the app binds
`127.0.0.1:8080` (host nginx fronts it) and `db` is only on the internal
Docker network.

### 2a. Swap (required — 2 GB RAM is tight)

The image build (`npm ci` + `vite build`) **and** running app + MySQL +
nginx together on 2 GB both need headroom. Add 2 GB swap once:

```bash
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
free -h
```

MySQL is already memory-trimmed in compose
(`--innodb-buffer-pool-size=128M --performance-schema=OFF`).

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
git checkout main          # deploy from main (after the rework is merged)
```

---

## 4. Install the production `.env`

The compose file mounts `./.env` into the app **read-only** and also reads
it for `${DB_*}` interpolation to configure the MySQL service. It must
exist **before** `docker compose up` — a bind mount to a missing path makes
Docker create a _directory_ called `.env` and the app breaks.

```bash
# from your machine:
scp .env user@<vps>:/var/www/siege-x-guide.alsagone.ovh/.env

cd /var/www/siege-x-guide.alsagone.ovh
chmod 600 .env
grep -E '^APP_KEY=base64:|^APP_URL=|^DB_DATABASE=|^DB_USERNAME=|^DB_PASSWORD=' .env
```

`.env` **must** contain:

| Key           | Requirement                                                        |
| ------------- | ------------------------------------------------------------------ |
| `APP_KEY`     | `base64:...` (mount is read-only — it can't be generated)          |
| `APP_URL`     | `https://siege-x-guide.alsagone.ovh`                               |
| `DB_DATABASE` | the schema name (created automatically by the MySQL container)     |
| `DB_USERNAME` | **must NOT be `root`** — the MySQL image rejects `MYSQL_USER=root` |
| `DB_PASSWORD` | non-empty; also used as the MySQL root password                    |

`DB_HOST`/`DB_CONNECTION`/`DB_PORT` in `.env` are irrelevant — Compose
forces `DB_HOST=db`, `DB_CONNECTION=mysql`, `DB_PORT=3306` on the app.
`.env` is gitignored and `.dockerignore`-excluded; it never enters git or
the image.

---

## 5. Build & start

```bash
cd /var/www/siege-x-guide.alsagone.ovh
docker compose build              # composer → vite → runtime (uses swap)
docker compose up -d
docker compose ps                 # app: running · db: healthy
docker compose logs -f app
# expect: "Waiting for database..." then migrate, then
#         "Empty database — seeding once." on the FIRST boot only
curl -I http://127.0.0.1:8080     # HTTP/1.1 200
```

**Always `docker compose build` before `up`** — `siege-x-guide` is a
local build-only image (`pull_policy: build`); a plain `up` on a host
that has never built it would otherwise try to pull it from Docker Hub
and fail with `pull access denied`.

First boot seeds the DB once; subsequent restarts log
`Database already populated — skipping seed.` and reuse the `db-data`
volume.

If `docker compose build` is OOM-killed: confirm step 2a swap is active and
retry, or build elsewhere and ship the image:
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

## 7. Redeploy / update (replaces deploy.sh)

```bash
cd /var/www/siege-x-guide.alsagone.ovh
git pull origin main
docker compose up -d --build
docker image prune -f
```

The entrypoint runs `php artisan migrate --force` (idempotent — applies any
new migrations) and **skips seeding** because the volume already has data.
Production caches are rebuilt. The MySQL data is **kept**.

**To refresh content from updated seeders** (destructive — wipes & rebuilds
from seeders, which is the intended content-update path since content is
seeder-only):

```bash
docker compose exec app php artisan migrate:fresh --seed --force
```

**Backup the DB** (cron-friendly):

```bash
docker compose exec db sh -c \
  'exec mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"' \
  > backup-$(date +%F).sql
```

---

## 8. Retire the old setup

Once the containers serve traffic, remove the old non-Docker vhost /
PHP-FPM config and any cron/`deploy.sh`, leaving only the reverse-proxy
server block from step 6. The old standalone MySQL on the host can be
stopped/removed — the app now uses the `db` container exclusively.

---

## Troubleshooting

- **`db` unhealthy / app stuck "Waiting for database"** — check
  `docker compose logs db`. Usually `MYSQL_USER=root` (set a non-root
  `DB_USERNAME` in `.env`) or an empty `DB_PASSWORD`.
- **`could not find driver`** — the runtime image must have `pdo_mysql`
  (the `webdevops/php-nginx` image bundles it; verify with
  `docker compose exec app php -m | grep pdo_mysql`).
- **First boot didn't seed** — only seeds when `operators` is empty;
  inspect `docker compose logs app` for the seed line, or force with
  `migrate:fresh --seed` (step 7).

---

## Quick reference

| Item               | Value                                                              |
| ------------------ | ------------------------------------------------------------------ |
| Project path       | `/var/www/siege-x-guide.alsagone.ovh`                              |
| Services           | `app` (127.0.0.1:8080→:80) · `db` (MySQL 8, internal only)         |
| Public ports       | 80/443 via host nginx                                              |
| Secrets / DB creds | `./.env` (bind-mounted + Compose interpolation)                    |
| DB persistence     | `db-data` volume; seeded once                                      |
| Content refresh    | `docker compose exec app php artisan migrate:fresh --seed --force` |
| Logs               | `docker compose logs -f app` / `db`                                |
| Update             | `git pull && docker compose up -d --build` (data kept)             |
| Build/runtime RAM  | needs 2 GB swap (step 2a)                                          |

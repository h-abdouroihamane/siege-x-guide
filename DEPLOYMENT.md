# Deployment — siege-x-guide on OVH (Docker)

Target: **OVH VPS/Dedicated** (root SSH, Debian/Ubuntu) · **2 vCore / 2 GB RAM / 40 GB disk**
Edge: **host nginx + Let's Encrypt** reverse-proxying the container
Domain: **siege-x-guide.alsagone.ovh**

The app is a read-only public guide running on **SQLite with seeder-only
content**. The Docker image is self-contained (no git pull at runtime) and
replaces the old `scripts/deploy.sh`. The production `.env` fetched from
the VPS is mounted read-only and supplies `APP_KEY` / `APP_URL`; the
Dockerfile forces `APP_ENV=production` + SQLite regardless of legacy `.env`
values (Laravel immutable dotenv — real env vars win over `.env`).

---

## 1. DNS (OVH Manager)

**Web Cloud → Domain names → `alsagone.ovh` → DNS zone**:

- **A** record: `siege-x-guide` → VPS IPv4
- (optional) **AAAA** → VPS IPv6

Confirm before requesting a certificate:

```bash
dig +short siege-x-guide.alsagone.ovh   # must return the VPS IP
```

---

## 2. Server prerequisites

SSH in, then:

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

Port **8080 is never opened publicly** — the container binds
`127.0.0.1:8080` and only the host nginx reaches it.

### 2a. Swap (required — 2 GB RAM is tight for the build)

`npm ci` + `vite build` inside the image build will OOM on 2 GB without
swap. Add 2 GB swap on the 40 GB disk (one-time):

```bash
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
free -h   # confirm swap is active
```

---

## 3. Get the code (private GitLab repo)

Add a **deploy key** so the server can clone:

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

The compose file mounts `./.env` into the container **read-only**. It must
exist **before** `docker compose up`, otherwise Docker creates a _directory_
named `.env` and the app breaks.

Copy the `.env` you fetched from the VPS into the project root:

```bash
# from your machine:
scp .env user@<vps>:/var/www/siege-x-guide.alsagone.ovh/.env

# on the VPS, verify it has a real key and the right URL:
cd /var/www/siege-x-guide.alsagone.ovh
grep -E '^APP_KEY=base64:|^APP_URL=' .env
chmod 600 .env
```

- `APP_KEY` **must** be `base64:...` (the entrypoint only auto-generates if
  missing, and the mount is read-only so it cannot).
- Set `APP_URL=https://siege-x-guide.alsagone.ovh`.
- `DB_*` / `APP_ENV` in this file are ignored on purpose — the image forces
  SQLite + production. No MySQL is needed.
- `.env` is gitignored and `.dockerignore`-excluded; it never enters git or
  the image.

---

## 5. Build & start

```bash
cd /var/www/siege-x-guide.alsagone.ovh
docker compose build          # composer → vite → runtime (uses swap)
docker compose up -d
docker compose ps             # "app" = running
docker compose logs -f app    # entrypoint: migrate:fresh --seed, caches
curl -I http://127.0.0.1:8080 # expect HTTP/1.1 200
```

If `docker compose build` is killed (OOM): confirm step 2a swap is active,
then retry. As a fallback you can build on a bigger machine and ship the
image: `docker save siege-x-guide | ssh <vps> 'docker load'`, then
`docker compose up -d` (skips the build).

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
sudo systemctl status certbot.timer   # auto-renewal armed
```

Open `https://siege-x-guide.alsagone.ovh` — the operators guide should load
over HTTPS. Asset paths are root-relative (`/operatorIcons/…`) so they
resolve correctly behind the proxy.

---

## 7. Redeploy / update (replaces deploy.sh)

```bash
cd /var/www/siege-x-guide.alsagone.ovh
git pull origin main
docker compose up -d --build
docker image prune -f
```

No migrate step, no asset shuffle, no prompts. The entrypoint runs
`migrate:fresh --seed` + production caches on every start — **by design**,
since content is seeder-only. The SQLite DB is therefore rebuilt on each
restart. To persist it across restarts instead, uncomment the `sqlite:`
volume block in `docker-compose.yml`.

Optional `redeploy.sh` on the server:

```bash
#!/bin/bash
set -euo pipefail
cd /var/www/siege-x-guide.alsagone.ovh
git pull origin main
docker compose up -d --build
docker image prune -f
```

---

## 8. Retire the old setup

Once the container serves traffic, remove any old non-Docker vhost /
PHP-FPM config for this site and any cron/`deploy.sh` invocation, leaving
only the reverse-proxy server block from step 6.

---

## Quick reference

| Item           | Value                                          |
| -------------- | ---------------------------------------------- |
| Project path   | `/var/www/siege-x-guide.alsagone.ovh`          |
| Container bind | `127.0.0.1:8080 → :80` (private)               |
| Public ports   | 80/443 via host nginx                          |
| Secrets        | `./.env` bind-mounted read-only                |
| DB             | in-container SQLite, reseeded each start       |
| Logs           | `docker compose logs -f app`                   |
| Restart        | `unless-stopped` (+ `systemctl enable docker`) |
| Update         | `git pull && docker compose up -d --build`     |
| Build RAM      | needs 2 GB swap (step 2a)                      |

#!/usr/bin/env bash
# Redeploy siege-x-guide (Docker) on the OVH VPS.
#
# Pull main, rebuild the image, recreate the container. The seeded
# SQLite DB is baked into the image at build time, so a single deploy
# command refreshes both code AND content — there is no separate
# `--seed` flow anymore.
set -euo pipefail

cd "$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

echo "==> Syncing main"
git checkout main
git pull --ff-only origin main

echo "==> Rebuilding image & recreating container"
docker compose up -d --build
docker image prune -f

echo "==> Waiting for app"
for _ in $(seq 1 30); do
    curl -fsS -o /dev/null http://127.0.0.1:8080 && break
    sleep 2
done

if curl -fsS -o /dev/null http://127.0.0.1:8080; then
    echo "==> OK — live at https://siege-x-guide.alsagone.ovh"
else
    echo "!! app not responding on :8080 — docker compose logs --tail=50 app" >&2
    exit 1
fi

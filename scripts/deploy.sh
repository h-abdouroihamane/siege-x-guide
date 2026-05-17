#!/usr/bin/env bash
# Redeploy siege-x-guide (Docker) on the OVH VPS.
#
#   ./scripts/deploy.sh            routine deploy — pull main, rebuild
#                                  image, recreate containers. DATA KEPT
#                                  (entrypoint runs `migrate --force` and
#                                  seeds only if the DB is empty).
#
#   ./scripts/deploy.sh --seed     ALSO refresh content from the seeders
#                                  (`migrate:fresh --seed`). Use this when
#                                  seeders changed (new operator, etc.).
#                                  Destructive rebuild of the DB from the
#                                  seeders — content is seeder-only so
#                                  nothing else is lost. Brief blip while
#                                  the seeders run.
set -euo pipefail

cd "$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

SEED=false
[[ "${1:-}" == "--seed" ]] && SEED=true

echo "==> Syncing main"
git checkout main
git pull --ff-only origin main

echo "==> Rebuilding image & recreating containers"
docker compose up -d --build
docker image prune -f

echo "==> Waiting for app"
for _ in $(seq 1 30); do
    curl -fsS -o /dev/null http://127.0.0.1:8080 && break
    sleep 2
done

if $SEED; then
    echo "==> CONTENT REFRESH: migrate:fresh --seed (rebuilds the DB from seeders)"
    # Detached: runs inside the container independent of this SSH
    # session, so a dropped connection cannot interrupt it and leave a
    # half-migrated DB / restart loop.
    docker compose exec -d app php artisan migrate:fresh --seed --force
    echo "==> Reseed running in-container. Track it with:"
    echo "    docker compose logs -f app"
    echo "    curl -s http://127.0.0.1:8080/operators/all | head -c 120"
    exit 0
fi

if curl -fsS -o /dev/null http://127.0.0.1:8080; then
    echo "==> OK — live at https://siege-x-guide.alsagone.ovh"
else
    echo "!! app not responding on :8080 — docker compose logs --tail=50 app" >&2
    exit 1
fi

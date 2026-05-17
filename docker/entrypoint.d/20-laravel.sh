#!/bin/bash
# Laravel provisioning — runs on every container start (webdevops hook).
# Replaces scripts/deploy.sh: no git pull, no interactive prompts, no
# host SSH. Uses the persistent MySQL service; content is seeder-only and
# fully reproducible, so it is seeded ONCE (only when the DB is empty)
# and then persists across restarts/redeploys.
set -euo pipefail

cd /app

# .env: a real one is bind-mounted in production; fall back to the
# example only for a throwaway run.
if [ ! -f .env ]; then
    cp .env.example .env
fi

# APP_KEY: only generate if neither the environment nor .env provides one
# (the production .env is mounted read-only and already has a key).
if [ -z "${APP_KEY:-}" ] && ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

# Wait for the MySQL service to accept connections (compose also gates
# this with depends_on: service_healthy, but retry defensively).
for i in $(seq 1 30); do
    if php artisan db:show >/dev/null 2>&1; then
        break
    fi
    echo "Waiting for database... ($i/30)"
    sleep 2
done

# Apply migrations (idempotent — safe on every start).
php artisan migrate --force

# Seed exactly once: only when the domain data is absent. On subsequent
# starts the persisted volume already has the rows, so this is skipped.
COUNT="$(php artisan tinker --execute='echo (int) (\Illuminate\Support\Facades\Schema::hasTable("operators") ? \App\Models\Operator::query()->count() : 0);' 2>/dev/null | tail -n1 | tr -cd '0-9')"
if [ "${COUNT:-0}" -eq 0 ]; then
    echo "Empty database — seeding once."
    php artisan db:seed --force
else
    echo "Database already populated (${COUNT} operators) — skipping seed."
fi

# Production caches.
php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R application:application storage bootstrap/cache

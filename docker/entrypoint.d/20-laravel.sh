#!/bin/bash
# Laravel provisioning — runs on every container start (webdevops hook).
# Replaces scripts/deploy.sh: no git pull, no interactive prompts, no
# host SSH. Content is seeder-only so the DB is migrated fresh + seeded.
set -euo pipefail

cd /app

# .env: prefer an injected one; fall back to the example for a
# self-contained demo run.
if [ ! -f .env ]; then
    cp .env.example .env
fi

# APP_KEY: only generate if neither env nor .env provides one.
if [ -z "${APP_KEY:-}" ] && ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

# SQLite store.
mkdir -p database
touch database/database.sqlite

# Seeder-only content: rebuild the schema and reseed every start.
php artisan migrate:fresh --seed --force

# Production caches.
php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R application:application database storage bootstrap/cache

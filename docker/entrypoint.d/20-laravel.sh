#!/bin/bash
# Laravel provisioning — runs on every container start (webdevops hook).
# The seeded SQLite DB is baked into the image at build time, so this
# only has to warm the production caches. No migrate, no seed, no DB
# wait.
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

# Production caches.
php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R application:application storage bootstrap/cache

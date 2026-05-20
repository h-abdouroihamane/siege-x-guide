# syntax=docker/dockerfile:1

# ---------------------------------------------------------------------------
# Stage 1 — PHP dependencies (no dev)
# ---------------------------------------------------------------------------
FROM composer:2 AS vendor
WORKDIR /app
COPY . .
RUN composer install \
    --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# ---------------------------------------------------------------------------
# Stage 2 — Frontend build (needs vendor/ for the ziggy-js Vite alias)
# ---------------------------------------------------------------------------
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# ---------------------------------------------------------------------------
# Stage 3 — Runtime (nginx + php-fpm, non-root)
# ---------------------------------------------------------------------------
FROM webdevops/php-nginx:8.4-alpine AS runtime

ENV WEB_DOCUMENT_ROOT=/app/public \
    APP_ENV=production \
    APP_DEBUG=false

WORKDIR /app

# Application code + built artifacts. The image is self-contained: it does
# NOT pull from git at runtime (that was scripts/deploy.sh's job).
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# Bake the seeded SQLite database into the image. Content is seeder-only
# and fully reproducible from git, so every build ships a freshly seeded
# DB and no runtime volume / migration / seed dance is needed. The .env
# is throwaway: at runtime the production .env is bind-mounted over it.
RUN cp .env.example .env \
    && php artisan key:generate --force \
    && touch database/database.sqlite \
    && php artisan migrate --force \
    && php artisan db:seed --force \
    && rm .env

# Provision script runs on every container start (webdevops hook dir).
COPY docker/entrypoint.d/20-laravel.sh /opt/docker/provision/entrypoint.d/20-laravel.sh
RUN chmod +x /opt/docker/provision/entrypoint.d/20-laravel.sh \
    && chown -R application:application /app

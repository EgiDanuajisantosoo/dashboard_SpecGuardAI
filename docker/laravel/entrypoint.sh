#!/bin/sh
set -e

echo "============================================"
echo " SpecGuard AI — Laravel Container"
echo " Role: ${CONTAINER_ROLE:-web}"
echo "============================================"

# Ensure .env exists (env vars come from docker-compose env_file)
if [ ! -f .env ]; then
    touch .env
fi

# Ensure storage directories exist with correct permissions
mkdir -p storage/logs storage/framework/{cache,sessions,views}
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

if [ "$CONTAINER_ROLE" = "web" ]; then
    echo ">> Running migrations..."
    php artisan migrate --force 2>&1 || echo "Migration warning (may be OK on first run)"

    echo ">> Caching configuration..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    echo ">> Starting nginx + php-fpm via supervisord..."
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

elif [ "$CONTAINER_ROLE" = "worker" ]; then
    echo ">> Starting queue worker (redis)..."
    exec php artisan queue:work redis \
        --sleep=3 \
        --tries=3 \
        --timeout=300 \
        --max-jobs=1000 \
        --max-time=3600

else
    echo ">> Running custom command: $@"
    exec "$@"
fi

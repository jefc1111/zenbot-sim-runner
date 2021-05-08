#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$env" != "local" ]; then
    echo "Caching configuration..."
    (cd /var/www/html && php artisan config:cache && php artisan route:cache && php artisan view:cache)
fi

if [ "$role" = "app" ]; then

    exec apache2-foreground

elif [ "$role" = "queue" ]; then

    echo "Queue role"
    exit 1

elif [ "$role" = "scheduler" ]; then

    echo "Scheduler role"
    exit 1

else
    echo "Could not match the container role \"$role\""
    exit 1
fi

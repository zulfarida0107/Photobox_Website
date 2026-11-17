#!/bin/bash
set -e

# Support dynamic PORT from Railway (default to 80 if not set)
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Auto run migrations if database is ready
if [ "$AUTO_MIGRATE" = "true" ] || [ "$CI_ENVIRONMENT" = "production" ]; then
    echo "Running CodeIgniter database migrations..."
    php spark migrate --all || true
fi

exec "$@"

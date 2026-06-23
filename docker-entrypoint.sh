#!/bin/sh
set -e

echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
until php -r "
new PDO(
    'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
    getenv('DB_USERNAME'),
    getenv('DB_PASSWORD')
);
" 2>/dev/null; do
    sleep 2
done
echo "MySQL is ready."

php artisan migrate --force
php artisan orchid:admin "${ADMIN_NAME}" "${ADMIN_EMAIL}" "${ADMIN_PASSWORD}" --force 2>/dev/null || true
php artisan storage:link --force 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port=8000

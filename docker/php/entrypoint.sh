#!/bin/bash
set -e

export COMPOSER_PROCESS_TIMEOUT=2000

git config --global --add safe.directory /var/www

if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

if [ -d "vendor" ] && [ ! -f "vendor/autoload.php" ]; then
    echo "Dossier vendor corrompu détecté. Suppression..."
    rm -rf vendor
fi

if [ ! -f "vendor/autoload.php" ]; then
    echo "Installing Composer dependencies..."
    if [ -f "composer.lock" ]; then
        rm composer.lock
    fi
    composer install --no-interaction --optimize-autoloader --ignore-platform-reqs
else
    echo "Dependencies verified."
fi

if [ ! -d "node_modules" ]; then
    echo "Installing NPM dependencies..."
    npm install
    npm run build
fi

php artisan key:generate

echo "Waiting for MySQL..."
until mysql -h db -u user -ppassword --skip-ssl -e 'select 1'; do
  >&2 echo "MySQL is unavailable - sleeping"
  sleep 5
done

if [ "$IS_PRIMARY" = "true" ]; then
    echo "Running Migrations..."
    php artisan migrate:fresh --seed --force || true
fi

echo "Starting PHP-FPM..."
exec "$@"
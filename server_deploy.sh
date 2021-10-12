#!/bin/bash
set -e

echo "Deploying application ..."

# Enter maintanance mode
php artisan down
    # Update codebase
    git pull origin master

    # Install dependencies based on lock file
    composer install --no-interaction --prefer-dist --optimize-autoloader

    # Migrate database
    #php artisan migrate --force

    # Clear cache
    php artisan optimize

    # Reload PHP to update opcache
    #echo "" | sudo -S service php7.4-fpm reload
# Exit maintenance mode
php artisan up

chown -R www-data:www-data storage/ bootstrap/cache/

echo "🚀 Application deployed!"

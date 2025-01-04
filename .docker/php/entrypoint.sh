#!/bin/bash

cd /var/www/html

./.docker/php/wait-for-it.sh mysql 3306 -- echo "MySQL is ready"

# Execute Laravel commands
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan optimize
php artisan scribe:generate

# Keep the container running
exec "$@"

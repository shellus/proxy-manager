#!/bin/bash
set -eo pipefail
shopt -s nullglob

chmod -R 777 storage

php artisan wait-mysql

echo 'start migrate ...'
php artisan migrate --force
echo 'done migrate !'

echo 'start deploy all host ...'
php artisan proxy:deploy-all
echo 'done deploy all host !'

echo 'running supervisord ...'
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf

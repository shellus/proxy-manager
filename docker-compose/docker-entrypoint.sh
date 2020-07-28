#!/bin/bash
set -eo pipefail
shopt -s nullglob

chmod -R 777 storage

php artisan wait-mysql

# 这里可以考虑改成，只有第一次启动才做，用一个 touch /docker-init 来判断
# 每次开机执行没必要
echo 'start migrate ...'
php artisan migrate --force
echo 'done migrate !'

echo 'start deploy all host ...'
php artisan proxy:deploy-all
echo 'done deploy all host !'

echo 'running supervisord ...'
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf

#!/bin/bash
set -eo pipefail
shopt -s nullglob

# 检查权限
#chown -R mysql:mysql /var/lib/mysql

if [ "`ls /config/mysql`" = "" ]; then
    cp -r /var/lib/mysql_init/* /config/mysql/
else
    echo 'found exists mysql data dir !'
fi

# 等待mysql启动后部署所有host
sleep 10 && \
echo 'start deploy all host ...' && \
php artisan proxy:deploy-all && \
echo 'done deploy all host !' &

echo 'running supervisord ...'
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf

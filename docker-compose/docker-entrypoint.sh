#!/bin/bash
set -eo pipefail
shopt -s nullglob

# 检查权限
#chown -R mysql:mysql /var/lib/mysql

if [ "`ls /var/lib/mysql`" = "" ]; then
    cp -r /var/lib/mysql_init/* /var/lib/mysql/
else
    echo 'found exists mysql data dir !'
fi


# 安装acme.sh

#if [[ -d "/root/.acme.sh/" && -f "/root/.acme.sh/acme.sh" ]]; then
#    echo 'found acme.sh file !'
#else
#    echo 'install acme.sh ...'
#
#fi

echo 'running supervisord ...'
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf

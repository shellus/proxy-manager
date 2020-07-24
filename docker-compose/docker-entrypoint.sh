#!/bin/bash
set -eo pipefail
shopt -s nullglob

# 检查权限
#chown -R mysql:mysql /var/lib/mysql

if [ "`ls /var/lib/mysql`" = "" ]; then
    echo 'init mysql data dir...'
    # 初始化数据库 initialize-insecure 才是初始化并空root密码
    mysqld --initialize-insecure --user=mysql --datadir=/var/lib/mysql

    echo 'init mysql data dir success !'

    # 运行并创建数据库
    echo 'starting mysqld ...'
    /bin/bash -c "mysqld &" && sleep 5
    echo 'mysqld started !'

    echo 'create database and create mysql user ...'
    mysql -e "create database proxy_manager; CREATE USER 'root'@'127.0.0.1'; GRANT ALL ON *.* TO  'root'@'127.0.0.1'; FLUSH PRIVILEGES;"
    echo 'database created !'

    # 迁移数据
    echo 'running migrate ...'
    cd /webroot/proxy-manager/ && chmod -R 777 storage && php artisan migrate
    echo 'migrated ...'

    # 停止mysql
    killall mysqld
else
    echo 'found exists mysql data dir !'
fi


# 安装acme.sh

if [[ -d "/root/.acme.sh/" && -f "/root/.acme.sh/acme.sh" ]]; then
    echo 'found acme.sh file !'
else
#todo 安装报错了 ./acme.sh: 6999: shift: can't shift that many
    echo 'install acme.sh ...'
    cd /tmp && \
    git clone https://github.com/Neilpang/acme.sh.git && \
    cd acme.sh && \
    ./acme.sh --install --config-home
fi

echo 'running supervisord ...'
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf

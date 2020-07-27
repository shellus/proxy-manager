FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install --no-install-recommends -y \
    wget curl unzip \
    php-fpm php-dom php-pdo-mysql php-redis nginx git npm composer \
    supervisor cron sudo \
    redis-server mysql-server

# 应用代码下载+安装第三方库
RUN mkdir /webroot && cd /webroot && git clone https://github.com/shellus/proxy-manager.git && cd proxy-manager && git reset --hard 52bfea512f01254235253d8f956fd900e0e689fa
RUN cd /webroot/proxy-manager && composer install && cp .env.example .env && php artisan key:generate

RUN cd /webroot/proxy-manager/ && rm -rf proxy-manager-vue && git clone https://github.com/shellus/proxy-manager-vue.git && cd proxy-manager-vue && git reset --hard 768c44b76b5eb9e6ec206f8c7716a37f15cc2a5d
RUN cd /webroot/proxy-manager/proxy-manager-vue && npm install && cp .env.example .env.local && npm run build

# root权限运行一切，不然在ntfs或者samba文件系统，没法修改权限，问题大大
RUN sed -i 's/user\s*=\s*mysql/user=root/' /etc/mysql/mysql.conf.d/mysqld.cnf && mkdir /var/run/mysqld
RUN sed -i 's/user\s*=\s*www-data/user=root/' /etc/php/7.4/fpm/pool.d/www.conf

# 安装acme.sh
RUN cd /tmp && \
    git clone https://github.com/Neilpang/acme.sh.git && \
    cd acme.sh && \
    ./acme.sh --install
ENV LE_CONFIG_HOME="/config/acme.sh"

# 安装mysql
RUN echo 'starting mysqld ...' && \
    /bin/bash -c "mysqld &" && sleep 5 && \
    echo 'mysqld started !' && \
    echo 'create database and create mysql user ...' && \
    mysql -e "create database proxy_manager; CREATE USER 'root'@'127.0.0.1'; GRANT ALL ON *.* TO  'root'@'127.0.0.1'; FLUSH PRIVILEGES;" && \
    echo 'database created !' && \
    echo 'running migrate ...' && \
    cd /webroot/proxy-manager/ && chmod -R 777 storage && php artisan migrate --force && \
    echo 'migrated ...' && \
    killall mysqld

# mysql允许远程连接，只要不映射端口出去也就没事
RUN sed -i 's/bind-address.*/bind-address=0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf
RUN mv /var/lib/mysql /var/lib/mysql_init && mkdir -p /config/mysql

# SSL 配置
COPY options-ssl-nginx.conf /etc/nginx/
COPY ssl-dhparams.pem /etc/nginx/

# proxy-manager 的 vhost
COPY proxy-manager.conf /etc/nginx/proxy-manager.conf
RUN sed -i 's%sites-enabled/.*%&\n\tinclude /etc/nginx/proxy-manager.conf;%' /etc/nginx/nginx.conf
RUN rm /etc/nginx/sites-enabled/default

# 管理多个进程
COPY supervisord.conf /etc/supervisor/supervisord.conf
RUN touch /var/log/cron.log

# 入口脚本，用来初始化数据库
COPY docker-entrypoint.sh /usr/local/bin/
RUN ln -s /usr/local/bin/docker-entrypoint.sh /docker-entrypoint.sh && chmod +x /docker-entrypoint.sh

VOLUME /config
WORKDIR /webroot/proxy-manager
EXPOSE 80 443
ENTRYPOINT ["/docker-entrypoint.sh"]

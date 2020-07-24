FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install --no-install-recommends -y \
    wget curl unzip \
    php-fpm php-dom php-pdo-mysql php-redis nginx git npm composer \
    supervisor cron sudo \
    redis-server mysql-server

RUN curl https://get.acme.sh | sh

COPY supervisord.conf /etc/supervisor/supervisord.conf

RUN touch /var/log/cron.log

# root权限运行一切，不然在ntfs或者samba文件系统，没法修改权限，问题大大
RUN sed -i 's/user\s*=\s*mysql/user=root/' /etc/mysql/mysql.conf.d/mysqld.cnf && mkdir /var/run/mysqld
RUN sed -i 's/user\s*=\s*www-data/user=root/' /etc/php/7.4/fpm/pool.d/www.conf

# 应用代码下载+安装第三方库
RUN mkdir /webroot && cd /webroot && git clone https://github.com/shellus/proxy-manager.git
RUN cd /webroot/proxy-manager && composer install && cp .env.example .env && php artisan key:generate

RUN cd /webroot/proxy-manager/ && rm -rf proxy-manager-vue && git clone https://github.com/shellus/proxy-manager-vue.git
RUN cd /webroot/proxy-manager/proxy-manager-vue && npm install && cp .env.example .env.local && npm run build

# 入口脚本，用来初始化数据库
COPY docker-entrypoint.sh /usr/local/bin/
RUN ln -s usr/local/bin/docker-entrypoint.sh /entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]

WORKDIR /webroot/proxy-manager

EXPOSE 80 443

VOLUME /var/lib/mysql /etc/nginx/sites-enabled

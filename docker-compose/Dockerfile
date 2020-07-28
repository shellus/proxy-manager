FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install --no-install-recommends -y \
    wget curl unzip \
    php-fpm php-dom php-pdo-mysql php-redis nginx git npm composer \
    supervisor cron sudo \
    redis-server mysql-client

# 应用代码下载+安装第三方库
# TODO 可以考虑用分支部署，或者tag release部署
RUN mkdir /webroot && cd /webroot && git clone https://github.com/shellus/proxy-manager.git && cd proxy-manager && git reset --hard v1.0.0
RUN cd /webroot/proxy-manager && composer install && cp .env.example .env && php artisan key:generate

RUN cd /webroot/proxy-manager/ && rm -rf proxy-manager-vue && git clone https://github.com/shellus/proxy-manager-vue.git && cd proxy-manager-vue && git reset --hard v1.0.0
RUN cd /webroot/proxy-manager/proxy-manager-vue && npm install && cp .env.example .env.local && npm run build

# root权限运行一切，不然在ntfs或者samba文件系统，没法修改权限，问题大大
RUN sed -i 's/user\s*=\s*www-data/user=root/' /etc/php/7.4/fpm/pool.d/www.conf

# 安装acme.sh
RUN cd /tmp && \
    git clone https://github.com/Neilpang/acme.sh.git && \
    cd acme.sh && \
    ./acme.sh --install
ENV LE_CONFIG_HOME="/config/acme.sh"

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

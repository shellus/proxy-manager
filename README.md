# 代理管理器
设计这个程序的初衷是因为在docker-compose项目下管理nginx反向代理有点不方便，在网上找了很久，发现一个 [nginx-proxy-manager](https://github.com/jc21/nginx-proxy-manager)
但是它的证书签发只支持webroot方式，在国内家庭网络没有80端口的情况下没法使用。提了issue问能不能支持acme.sh的dns api，没有得到回复，遂决定自己写一个

## docker下使用
1. 要使用代理的容器，需要使用同一个网络，例如在docker-compose中需要在容器配置增加
```yaml
    networks:
      - default
      - nginx_net
```
在 `docker-compose.yml` 最底下增加
```yaml
networks:
  nginx_net:
    external: true
```
2. 访问管理端口，添加代理，源站地址填写 `http://容器名:服务端口` 即可 


## 手动安装
除非你需要开发调试本项目，否则不建议手动安装
1. clone 本项目，执行 `composer install`, cd 到 `proxy-manager-vue` 执行 `npm install`
2. 修改项目下的 `.env` 配置好二进制文件路径（nginx等），修改 `proxy-manager-vue` 下的 `.env.local` 将后端地址指向本机地址
2. 在项目下执行`php artisan serve`,  cd 到 `proxy-manager-vue` 执行 `npm serve`

有任何疑问，请提Issue

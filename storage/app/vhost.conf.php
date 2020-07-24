<?php /** @var \App\External\NginxVhost $conf */ ?>
server {
    server_name <?=$conf->domains?>;
    listen <?=$conf->http_port?>;

<?php if($conf->enable_https): ?>
    listen <?=$conf->https_port?> ssl <?=$conf->enable_http2?'http2':''?>;
    ssl_certificate <?=$conf->cert_path?>;
    ssl_certificate_key <?=$conf->cert_key_path?>;
    include /etc/nginx/options-ssl-nginx.conf;
    ssl_dhparam /etc/nginx/ssl-dhparams.pem;

<?php if($conf->enable_https_only): ?>
    if ($scheme = http) {
        return 301 https://$host$request_uri;
    }
<?php endif; ?>
<?php if($conf->enable_https_hsts): ?>
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
<?php endif; ?>
<?php endif; ?>

    location / {
        proxy_pass <?=$conf->target_address?>;
        proxy_set_header Host $host;
    }
}

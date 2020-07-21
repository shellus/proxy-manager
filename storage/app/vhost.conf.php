server {
    server_name <?=$domains?>;
    listen <?=$http_port?>;

<?php if($enable_https): ?>
    listen <?=$https_port?> ssl <?=$enable_http2?'http2':''?>;
    ssl_certificate <?=$cert_path?>;
    ssl_certificate_key <?=$cert_key_path?>;
    include /etc/nginx/options-ssl-nginx.conf;
    ssl_dhparam /etc/nginx/ssl-dhparams.pem;

<?php if($enable_https_only): ?>
    if ($protocol = http) {
        return 301 https://$host$request_uri;
    }
<?php endif; ?>
<?php if($enable_https_hsts): ?>
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
<?php endif; ?>
<?php endif; ?>

    location / {
        proxy_pass <?=$target_address?>;
        proxy_set_header Host $host;
    }
}

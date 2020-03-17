**LNAMP笔记（Debian 10.3 + Nginx 1.14 + Apache 2.4 + MariaDB 10.3 + PHP 7.3 + Tomcat 9.0 + Python 3.7）**

~~/etc/apt/sources.list~~
```
deb http://mirrors.tencentyun.com/debian            buster          main  contrib   non-free
deb http://mirrors.tencentyun.com/debian            buster-updates  main  contrib   non-free
deb http://mirrors.tencentyun.com/debian-security/  buster/updates  main  non-free  contrib
```
```
dpkg-reconfigure tzdata
apt update
apt upgrade
apt dist-upgrade
```
```
apt install mariadb-server mariadb-client
mysql_secure_installation
mysql -u root -p
MariaDB>grant select,insert,update,delete on *.* to 'user123'@'%' Identified by 'pass123'; 
```
```
wget https://files.phpmyadmin.net/phpMyAdmin/5.0.1/phpMyAdmin-5.0.1-all-languages.tar.gz
tar zxvf phpMyAdmin-5.0.1-all-languages.tar.gz
mv phpMyAdmin-5.0.1-all-languages /usr/share/phpmyadmin
cp -pr /usr/share/phpmyadmin/config.sample.inc.php  /usr/share/phpmyadmin/config.inc.php
```
```
apt install apache2 php libapache2-mod-php php-gd php-mysql php-mcrypt php-memcached
```
/etc/apache2/ports.conf
```
Listen 127.0.0.1:81
```
/etc/apache2/sites-available/default
```
ServerName 127.0.0.1                         /etc/apache2/conf-available/security.conf
<VirtualHost *:81>                           ServerTokens Prod
    ServerAdmin xxx@xxx.net                  ServerSignature Off
    DocumentRoot /var/www
    …                                        /etc/php/7.3/apache2/php.ini
</VirtualHost>                               expose_php = off
                                             upload_max_filesize = 10M
a2enmod rewrite ssl                          date.timezone = Asia/Shanghai

/etc/apache2/apache2.conf                    /etc/nginx/nginx.conf
AllowOverride All                            server_tokens = off
```
```
apt install openjdk-11-jdk tomcat9 tomcat9-admin libmariadb-java
cp /usr/share/java/mariadb-java-client.jar /usr/share/tomcat9/lib/
```
/etc/tomcat9/tomcat-users.xml
```
<role rolename="admin-gui"/>
<role rolename="manager-gui"/>
<user username="admin" password="xxx" roles="admin-gui,manager-gui"/>
```
/etc/tomcat9/server.xml
```
<Connector port="8080" address="127.0.0.1" protocol="HTTP/1.1" connectionTimeout="20000"
 redirectPort="8443"/>
<Connector port="8009" protocol="AJP1.3" redirectPort="8443"/>        #取消注释
<Context path="" docBase="/var/www" debug="0" reloadable="true"/>     #在<Host>节点里面添加
```
```
apt install apache2 libapache2-mod-wsgi-py3 python3-pip libmariadbd-dev
pip3 install --upgrade pip
```
/etc/pip.conf
```
[global]
trusted-host = mirrors.aliyun.com
index-url = https://mirrors.aliyun.com/pypi/simple
extra-index-url = https://pypi.tuna.tsinghua.edu.cn/simple
```
/etc/apache2/sites-available/000-default.conf
```
<VirtualHost *:81>
    ServerName xxx.net
    WSGIScriptAlias / /var/www/demo/django.wsgi
    <Directory "/var/www/demo">
        Options FollowSymLinks Indexes
        AllowOverride all
        Require all granted
    </Directory>

    Alias /robots.txt /var/www/demo/static/robots.txt
    Alias /static /var/www/demo/static
    <Location "/static">
        SetHandler None
    </Location>
    <Directory "/var/www/demo/static">
        Require all granted
    </Directory>

    ErrorLog "/var/log/apache2/py-error.log"
    CustomLog "/var/log/apache2/py-access.log" combined
</VirtualHost>
```
```
apt install nginx libapache2-mod-rpaf
```
/etc/apache2/mods-enable/rpaf.conf
```
RPAheader X-Forwarded-For
```
/etc/nginx/proxy_params
```
proxy_set_header            Host $host;
proxy_set_header            X-Real-IP $remote_addr;
proxy_set_header            X-Forwarded-For $proxy_add_x_forwarded_for;
client_max_body_size        10m;
client_body_buffer_size     128k;
proxy_connect_timeout       30;
proxy_send_timeout          30;
proxy_read_timeout          60;
proxy_buffers               8 128k;
```
/etc/nginx/sites-available/default
```
upstream php {
    server 127.0.0.1:81;
}
upstream java {
    server 127.0.0.1:8080;
}
server {
    server_name _;
    return 404;
}
server {
    listen 80;
    root /var/www;
    index index.php index.html;
    server_name php.xxx.net;
    proxy_redirect http://php.xxx.net:81/ /;
    location / {
        proxy_pass http://php;
        include proxy_params;
    }
}
server {
    listen 80;
    server_name java.xxx.net;
    location / {
        proxy_pass http://java;
        proxy_redirect off;
        include proxy_params;
    }
}
```
```
apt install certbot
certbot certonly --webroot -w /var/www/demo -d xxx.net -m xxx@live.cn --agree-tos
```
```
certbot renew --dry-run
crontab -e
30 2 * * 1 /usr/bin/certbot renew  >> /var/log/le-renew.log
```
/etc/nginx/sites-enabled/default
```
openssl dhparam -out /etc/ssl/certs/dhparam.pem 4096
```
```
listen 443 ssl http2;

add_header Strict-Transport-Security "max-age=63072000; includeSubdomains; preload";
add_header X-Frame-Options DENY;
add_header X-Content-Type-Options nosniff;

ssl_protocols TLSv1.2 TLSv1.3;
ssl_ciphers TLS-CHACHA20-POLY1305-SHA256:TLS-AES-256-GCM-SHA384:TLS-AES-128-GCM-SHA256:HIGH:!aNULL:!MD5;
ssl_prefer_server_ciphers on;
ssl_session_cache shared:SSL:10m;

ssl_certificate /etc/letsencrypt/live/xxx.net/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/xxx.net/privkey.pem;
ssl_trusted_certificate /etc/letsencrypt/live/xxx.net/chain.pem;
ssl_dhparam /etc/ssl/certs/dhparam.pem;
```

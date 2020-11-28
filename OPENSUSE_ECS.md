**openSUSE笔记（openSUSE 15.2 + Nginx 1.16 + Apache 2.4 + MariaDB 10.4 + PHP 7.4 + Tomcat 9.0 + Python 3.6）**
```
zypper refresh
zypper update
zypper dup
```
```
zypper install mariadb mariadb-tools
systemctl start mariadb.service
systemctl enable mariadb.service
mysql_secure_installation
```
```
mysql -u root -p
MariaDB> grant select,insert,update,delete on *.* to 'user123'@'%' Identified by 'pass123'; 
MariaDB> flush privileges;
```
```
zypper install apache2
systemctl start apache2.service
systemctl enable apache2.service
/srv/www/htdocs/
```
```
zypper install php7 php7-mysql php7-gd php7-mbstring apache2-mod_php7 phpMyAdmin php-composer
a2enmod php7
systemctl restart apache2.service
```
/etc/apache2/listen.conf
```
Listen 81
```
/etc/php7/apache2/php.ini
```
expose_php = Off
date.timezone = Asia/Shanghai
```
```
composer self-update
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

mysql < /usr/share/doc/packages/phpMyAdmin/sql/create_tables.sql -u root -p
cp /srv/www/htdocs/phpMyAdmin/config.sample.inc.php  /srv/www/htdocs/phpMyAdmin/config.inc.php
```
/srv/www/htdocs/phpMyAdmin/config.inc.php
```
$cfg['blowfish_secret'] = 'odW{XxY{8UWxAw8q}wuF/6xw5{PiwmmV';
```
```
zypper install java-11-openjdk tomcat
systemctl start tomcat.service
systemctl enable tomcat.service
/srv/tomcat/webapps/
```
/usr/share/tomcat/conf/server.xml
```
<Connector port="8080" address="127.0.0.1" protocol="HTTP/1.1" connectionTimeout="20000"
 redirectPort="8443"/>
<Context path="" docBase="ROOT" debug="0" reloadable="true"/>     #在<Host>节点里面添加
```
```
zypper install libicu
rpm --import https://packages.microsoft.com/keys/microsoft.asc
wget https://packages.microsoft.com/config/opensuse/15/prod.repo
mv prod.repo /etc/zypp/repos.d/microsoft-prod.repo
chown root:root /etc/zypp/repos.d/microsoft-prod.repo
zypper install dotnet-sdk-5.0
```
```
zypper install nginx
systemctl start nginx.service
systemctl enable nginx.service
```
```
zypper install python3 python3-pip python3-setuptools python3-wheel

pip3 install --upgrade pip

zypper install python3-certbot
certbot certonly --webroot -w /srv/www/htdocs -d xxx.net -m xxx@live.cn --agree-tos
```
```
certbot renew --dry-run
crontab -e
30 2 * * 1 /usr/bin/certbot renew  >> /var/log/le-renew.log
```
```
openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048
```
/etc/nginx/conf.d/default.conf
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
        server_name www.xxx.net;
        location / {
            index index.html;
        }
    }
    server {
        listen 443 ssl http2;
        server_name xxx.net;

        ssl_dhparam /etc/ssl/certs/dhparam.pem;

        ssl_session_timeout 1d;
        ssl_session_cache shared:SSL:10m;
        ssl_session_tickets off;

        ssl_protocols TLSv1.2 TLSv1.3;
        ssl_ciphers HIGH:!aNULL:!MD5;
        ssl_prefer_server_ciphers on;

        ssl_certificate /etc/letsencrypt/live/xxx.net/fullchain.pem;
        ssl_certificate_key /etc/letsencrypt/live/xxx.net/privkey.pem;
        ssl_trusted_certificate /etc/letsencrypt/live/xxx.net/chain.pem;

        ssl_stapling on;
        ssl_stapling_verify on;
        resolver 8.8.8.8 8.8.4.4 valid=300s;
        resolver_timeout 30s;

        location / {
            proxy_read_timeout 300s;
            proxy_pass http://java;
            proxy_redirect off;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        }

        location /phpMyAdmin/ {
            proxy_pass http://php;
            proxy_set_header Host $host:443;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        }
    }
```

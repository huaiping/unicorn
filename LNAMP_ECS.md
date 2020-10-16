**LNAMP笔记（Debian 10.6 + Nginx 1.14 + Apache 2.4 + MariaDB 10.3 + PHP 7.3 + Tomcat 9.0 + Python 3.7）**

~~/etc/apt/sources.list~~
```
deb http://mirrors.aliyun.com/debian            buster          main  contrib   non-free
deb http://mirrors.aliyun.com/debian            buster-updates  main  contrib   non-free
deb http://mirrors.aliyun.com/debian-security/  buster/updates  main  non-free  contrib
```
```
dpkg-reconfigure tzdata
apt update
apt upgrade
apt full-upgrade
```
```
apt install mariadb-server mariadb-client
mysql_secure_installation
```
```
mysql -u root -p
MariaDB> grant select,insert,update,delete on *.* to 'user123'@'%' Identified by 'pass123'; 
MariaDB> flush privileges;
```
```
apt install apache2 php libapache2-mod-php
apt install php-bcmath php-gd php-json php-mbstring php-mysql php-tokenizer php-xml php-zip
```
```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```
```
wget https://files.phpmyadmin.net/phpMyAdmin/5.0.4/phpMyAdmin-5.0.4-all-languages.tar.gz
tar -zxvf phpMyAdmin-5.0.4-all-languages.tar.gz
mv phpMyAdmin-5.0.4-all-languages /var/www/html/phpmyadmin
cp /var/www/html/phpmyadmin/config.sample.inc.php  /var/www/html/phpmyadmin/config.inc.php
```
/var/www/html/phpmyadmin/config.inc.php
```
$cfg['blowfish_secret'] = 'CHBj{O6P5]c8LE428ltUqpyp6xaJ2xN5';
```
```
mysql < /var/www/html/phpmyadmin/sql/create_tables.sql -u root -p
mkdir /var/www/html/phpmyadmin/tmp
chmod 777 /var/www/html/phpmyadmin/tmp
```
/etc/apache2/ports.conf
```
Listen 127.0.0.1:81
```
/etc/apache2/sites-available/000-default.conf
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
apt install openjdk-11-jdk tomcat9 libmariadb-java
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
<Connector port="8009" protocol="AJP1.3" redirectPort="8443"/>    #取消注释
<Context path="" docBase="ROOT" debug="0" reloadable="true"/>     #在<Host>节点里面添加
```
```
wget -O- https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > microsoft.asc.gpg
mv microsoft.asc.gpg /etc/apt/trusted.gpg.d/
wget https://packages.microsoft.com/config/debian/10/prod.list
mv prod.list /etc/apt/sources.list.d/microsoft-prod.list
chown root:root /etc/apt/trusted.gpg.d/microsoft.asc.gpg
chown root:root /etc/apt/sources.list.d/microsoft-prod.list
apt install dotnet-sdk-3.1
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
```
apt install certbot
certbot certonly --webroot -w /var/www/html -d xxx.net -m xxx@live.cn --agree-tos
```
```
certbot renew --dry-run
crontab -e
30 2 * * 1 /usr/bin/certbot renew  >> /var/log/le-renew.log
```
```
openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048
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

        add_header Strict-Transport-Security "max-age=63072000; includeSubdomains; preload";
        add_header X-Frame-Options DENY;
        add_header X-Content-Type-Options nosniff;

        location / {
            proxy_pass http://java;
            proxy_redirect off;
            include proxy_params;
        }

        location /phpmyadmin/ {
            proxy_pass http://php;
            include proxy_params;
        }
    }
```

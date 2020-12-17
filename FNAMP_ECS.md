**FNAMP笔记（FreeBSD 11.3 + Nginx 1.18 + Apache 2.4 + MariaDB 10.4 + PHP 7.4 + Tomcat 9.0 + Python 3.7）**
```
tzsetup
freebsd-update fetch
freebsd-update install

freebsd-update upgrade -r 11.4-RELEASE
freebsd-update install
shutdown -r now
freebsd-update install
```
/usr/local/etc/pkg/repos/FreeBSD.conf
```
FreeBSD: {
  url: "pkg+http://mirrors.ustc.edu.cn/freebsd-pkg/${ABI}/quarterly",
}

pkg update -f
```
```
pkg-static install -f pkg
pwd_mkdb -p /etc/master.passwd        # user 'mysql' disappeared during update
```
```
pkg install nginx apache24 mariadb104-server openjdk8 tomcat9 python3 py37-pip py37-certbot
pkg install php74 mod_php74 php74-gd php74-json php74-mbstring php74-mysqli php74-pdo_mysql \
 php74-session phpMyAdmin5-php74 ap24-mod_rpaf2 mysql-connector-java
pkg install node12 npm-node12
```
```
sysrc mysql_enable="YES"
service mysql-server start
mysql_secure_installation
sysrc mysql_args="--bind-address=127.0.0.1"
service mysql-server restart
```
```
mysql -u root -p
MariaDB> grant select,insert,update,delete on *.* to 'user123'@'%' Identified by 'pass123'; 
MariaDB> flush privileges;
```
```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

pip3 install --upgrade pip

cp /usr/local/share/java/class/mysql-connector-java.jar /usr/local/apache-tomcat-9/lib/

cp /usr/local/www/phpMyAdmin/config.sample.inc.php /usr/local/www/phpMyAdmin/config.inc.php
```
/usr/local/www/phpMyAdmin/config.inc.php
```
$cfg['blowfish_secret']='Fo1ec5u0n8lpG4hMCOICD8X8cUUTxOF1';
```
```
cp /usr/local/etc/php.ini-production /usr/local/etc/php.ini
```
/usr/local/etc/php.ini
```
expose_php = Off
date.timezone = Asia/Shanghai
```
/usr/local/etc/apache24/httpd.conf
```
Listen 81
ServerAdmin xxx@live.cn
ServerName localhost:81
DirectoryIndex index.php index.html

<FilesMatch "\.php$">
    SetHandler application/x-httpd-php
</FilesMatch>

Alias /phpmyadmin "/usr/local/www/phpMyAdmin"
<Directory "/usr/local/www/phpMyAdmin">
    Options None
    AllowOverride None
    Require all granted
</Directory>
```
```
LoadModule rewrite_module libexec/apache24/mod_rewrite.so
LoadModule rpaf_module libexec/apache24/mod_rpaf.so

RPAFenable On
RPAFsethostname On
RPAFproxy_ips 127.0.0.1
RPAFheader X-Forwarded-For
```
/etc/fstab
```
fdesc   /dev/fd         fdescfs         rw      0       0
proc    /proc           procfs          rw      0       0
```
/usr/local/apache-tomcat-9.0/conf/tomcat-users.xml
```
<role rolename="admin-gui"/>
<role rolename="manager-gui"/>
<user username="admin" password="xxx" roles="admin-gui,manager-gui"/>
```
/usr/local/apache-tomcat-9.0/conf/server.xml
```
<Connector port="8080" address="127.0.0.1" protocol="HTTP/1.1" connectionTimeout="20000" redirectPort="8443"/>
<Context path="" docBase="ROOT" debug="0" reloadable="true"/>     #在<Host>节点里面添加
```
```
sysrc apache24_enable="YES"
service apache24 start
sysrc nginx_enable="YES"
service nginx start
sysrc tomcat9_enable="YES"
service tomcat9 start
```
```
certbot certonly --webroot -w /usr/local/www/nginx -d xxx.net -m xxx@live.cn --agree-tos
```
/usr/local/etc/nginx/nginx.conf
```
http {
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

        ssl_session_timeout 1d;
        ssl_session_cache shared:SSL:10m;
        ssl_session_tickets off;

        ssl_protocols TLSv1.2 TLSv1.3;
        ssl_ciphers HIGH:!aNULL:!MD5;
        ssl_prefer_server_ciphers on;

        ssl_certificate /usr/local/etc/letsencrypt/live/xxx.net/fullchain.pem;
        ssl_certificate_key /usr/local/etc/letsencrypt/live/xxx.net/privkey.pem;
        ssl_trusted_certificate /usr/local/etc/letsencrypt/live/xxx.net/chain.pem;

        ssl_stapling on;
        ssl_stapling_verify on;
        resolver 8.8.8.8 8.8.4.4 valid=300s;
        resolver_timeout 30s;

        location / {
            proxy_pass http://java;
            proxy_redirect off;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        }

        location /phpmyadmin/ {
            proxy_pass http://php;
            proxy_set_header Host $host:443;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            client_max_body_size 10m;
            client_body_buffer_size 128k;
            proxy_connect_timeout 90;
            proxy_send_timeout 90;
            proxy_read_timeout 90;
            proxy_buffer_size 4 32k;
            proxy_temp_file_write_size 64k;
        }
    }
}
```

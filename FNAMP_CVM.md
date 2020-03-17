**FNAMP笔记（FreeBSD 12.1 + Nginx 1.8 + Apache 2.4 + MariaDB 10.2 + PHP 7.1 + Tomcat 8.5 + Nodejs 8.8）**

~~/etc/freebsd-update.conf~~
```
ServerName update.chinafreebsd.cn
```
```
freebsd-update -r 11.3-RELEASE upgrade
freebsd-update install
reboot
freebsd-update install
tzsetup
```
/etc/pkg/FreeBSD.conf
```
pkg0.nyi.FreeBSD.org
```
```
pkg-static install -f pkg
pwd_mkdb -p /etc/master.passwd        # user 'mysql' disappeared during update
```
```
pkg install nginx apache24 mariadb102-server openjdk8 tomcat85 py36-certbot
pkg install php74 mod_php74 php74-gd php74-mbstring php74-mcrypt php74-pdo_mysql php74-json
 php74-session php74-mysqli php74-ctype php74-filter ap24-mod_rpaf2 mysql-connector-java node npm
cp /usr/local/share/java/class/mysql-connector-java.jar /usr/local/apache-tomcat-8.5/lib/
```
```
sysrc apache24_enable=yes
service apache24 start
sysrc mysql_enable=yes
service mysql-server start
sysrc nginx_enable=yes
service nginx start
```
~~/usr/local/etc/mysql/my.cnf~~
```
[mysqld]
port = 3306
socket = /tmp/mysql.sock
bind-address = 127.0.0.1
```
```
/usr/local/etc/rc.d/mysql-server onestart
mysql_secure_installation

cd /usr/local/www/phpMyAdmin
cp config.sample.inc.php config.inc.php
```
/usr/local/www/phpMyAdmin/config.inc.php
```
$cfg['blowfish_secret']='xxx';
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
/usr/local/apache-tomcat-8.0/conf/tomcat-users.xml
```
<role rolename="admin-gui"/>
<role rolename="manager-gui"/>
<user username="admin" password="xxx" roles="admin-gui,manager-gui"/>
```
/usr/local/apache-tomcat-8.0/conf/server.xml
```
<Connector port="8080" address="127.0.0.1" protocol="HTTP/1.1" connectionTimeout="20000"
 redirectPort="8443"/>
<Context path="" docBase="/usr/local/www/apache24/data" debug="0" reloadable="true"/>
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
        server_name xxx.net;
        location / {
            proxy_pass http://java;
            proxy_redirect off;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        }
        location /phpmyadmin/ {
            proxy_pass http://php;
            proxy_set_header Host $host;
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
```
certbot certonly --webroot -w /usr/local/www/apache24/data -d xxx.net -d www.xxx.net
```
```
/usr/local/etc/rc.d/apache24 start     或 service apache24 start
/usr/local/etc/rc.d/mysql-server start 或 service mysql-server start
/usr/local/etc/rc.d/nginx start        或 service nginx start
/usr/local/etc/rc.d/tomcat85 start     或 service tomcat85 start
```

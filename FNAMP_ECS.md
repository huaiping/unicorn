**FNAMP笔记（FreeBSD 10.2 + Nginx 1.8 + Apache 2.4 + MySQL 5.6 + PHP 5.6 + Tomcat 8.0 + Python 2.7）**

```
#freebsd-update fetch
#freebsd-update install
```
```
pkg install nginx apache24 mysql56-server tomcat8
pkg install php56 mod_php56 php56-gd php56-mbstring php56-mcrypt php56-mysql php56-pdo_mysql phpmyadmin
 ap24-mod_jk ap24-mod_rpaf2 mysql-connector-java python py27-setuptools ap24-mod_wsgi4 node npm
cp /usr/local/share/java/class/mysql-connector-java.jar /usr/local/apache-tomcat-8.0/lib/
```
/etc/rc.conf
```
apache24_enable="YES"
mysql_enable="YES"
nginx_enable="YES"
tomcat8_enable="YES"
```
```
cp /usr/local/share/mysql/my-default.cnf  /usr/local/etc/my.cnf
```
/usr/local/etc/my.cnf
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
DirectoryIndex index.php index.jsp index.html

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
```
cd /usr/local/etc/apache24
cp mod_jk.conf.sample Includes/mod_jk.conf
cp workers.properties.sample Includes/workers.properties
```
/usr/local/etc/apache24/Includes/mod_jk.conf
```
JkWorkersFile etc/apache24/Includes/workers.properties
#JkMount /examples/* jsp-hostname
```
/usr/local/etc/apache24/Includes/workers.properties
```
worker.jsp-hostname.host=localhost
```
```
wget https://bootstrap.pypa.io/get-pip.py
python get-pip.py
vi /usr/local/etc/apache24/modules.d/270_mod_wsgi.conf
```
/usr/local/etc/nginx/nginx.conf
```
http {
    upstream webserver {
        server 127.0.0.1:81;
    }
    server {
        server_name _;
        return 404;
    }
    server {
        listen       80;
        server_name  localhost;
        location / {
            proxy_pass                   http://webserver;
            proxy_set_header             Host $host;
            proxy_set_header             X-Real-IP $remote_addr;
            proxy_set_header             X-Forwarded-For $proxy_add_x_forwarded_for;
            client_max_body_size         10m;
            client_body_buffer_size      128k;
            proxy_connect_timeout        90;
            proxy_send_timeout           90;
            proxy_read_timeout           90;
            proxy_buffer_size            4 32k;
            proxy_temp_file_write_size   64k;
        }
    }
}
```
```
/usr/local/etc/rc.d/apache24 start     或 service apache24 start
/usr/local/etc/rc.d/mysql-server start 或 service mysql-server start
/usr/local/etc/rc.d/nginx start        或 service nginx start
/usr/local/etc/rc.d/tomcat8 start      或 service tomcat8 start
```

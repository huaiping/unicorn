**LNAMP笔记（Debian 9.3 + Nginx 1.10 + Apache 2.4 + MariaDB 10.1 + PHP 7.0 + Tomcat 8.5 + Python 3.5）**

~~/etc/apt/sources.list~~
```
deb http://mirrors.tencentyun.com/debian           stretch          main  contrib  non-free
deb http://mirrors.tencentyun.com/debian           stretch-updates  main  contrib  non-free
deb http://mirrors.tencentyun.com/debian-security  stretch/updates  main
```
```
apt update
apt upgrade
apt dist-upgrade
apt install mariadb-server mariadb-client
apt install openjdk-8-jdk tomcat8 tomcat8-admin
apt install apache2 php libapache2-mod-php libapache2-mod-rpaf libapache2-mod-jk libmysql-java
 php-gd php-mysql php-mcrypt php-memcached phpmyadmin libapache2-mod-wsgi-py3 python3-pip
cp /usr/share/java/mysql-connector-java-5.1.42.jar /usr/share/tomcat8/lib/
```
```
mysql_secure_installation
ln -s /usr/share/phpmyadmin/ /var/www/
```
```
mysql -u root -p
MariaDB>grant select,insert,update,delete on *.* to 'user123'@'%' Identified by 'pass123'; 
```
/etc/apache2/ports.conf
```
Listen 127.0.0.1:81
```
/etc/apache2/mods-enable/rpaf.conf
```
RPAheader X-Forwarded-For
```
/etc/tomcat8/tomcat-users.xml
```
<role rolename="admin-gui"/>
<role rolename="manager-gui"/>
<user username="admin" password="xxx" roles="admin-gui,manager-gui"/>
```
/etc/tomcat8/server.xml
```
<Connector port="8080" address="127.0.0.1" protocol="HTTP/1.1" connectionTimeout="20000"
 redirectPort="8443"/>
<Connector port="8009" protocol="AJP1.3" redirectPort="8443"/>        #取消注释
<Context path="" docBase="/var/www" debug="0" reloadable="true"/>     #在<Host>节点里面添加
```
/etc/libapache2-mod-jk/workers.properties
```
workers.java_home=/usr/lib/jvm/java-8-openjdk-amd64
```
/etc/apache2/sites-available/default
```
ServerName 127.0.0.1                         /etc/apache2/conf-available/security.conf
<VirtualHost *:81>                           ServerTokens Prod
    ServerAdmin xxx@xxx.net                  ServerSignature Off
    DocumentRoot /var/www
    …                                        /etc/php/7.0/apache2/php.ini
</VirtualHost>                               expose_php = off
                                             date.timezone = Asia/Shanghai
a2enmod rewrite ssl                          upload_max_filesize = 10M

/etc/apache2/apache2.conf                    /etc/nginx/nginx.conf
AllowOverride All                            server_tokens = off
```
```
apt install nginx
/etc/nginx/proxy_params
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
server {                                                upstream php {
    listen 80;                                              server 127.0.0.1:81;
    root /var/www;                                      }
    index index.html index.htm;                         upstream java {
    server_name localhost;                                  server 127.0.0.1:8080;
    proxy_redirect http://dev.xxx.net:81/ /;            }
    location ~ \.php$ {                                 server {
        proxy_pass http://127.0.0.1:81;                     server_name _;
        include proxy_params;                               return 404;
    }                                                   }
    location ~ .*.jsp$ {                                server {
        index index.jsp;                                    listen 80;
        proxy_pass http://127.0.0.1:8080;                   root /var/www;
        include proxy_params;                               index index.php index.html;
    }                                                       server_name php.xxx.net;
    location ~* (.*)\.(jpg|gif|png|html|js|css)$ {          proxy_redirect http://php.xxx.net:81/ /;
        root /var/www/html;                                 location / {
        expires 10m;                                            proxy_pass http://php;
    }                                                           include proxy_params;
}                                                           }
                                                        }
ssl_certificate /etc/ssl/xxx.crt;                       server {
ssl_certificate_key /etc/ssl/xxx.key;                       listen 80;
ssl_protocols添加 TLSv1.1 TLSv1.2                           server_name java.xxx.net;
                                                            location / {
                                                                proxy_pass http://java;
                                                                proxy_redirect off;
                                                                include proxy_params;
                                                            }
                                                        }
```
```
apt install certbot
certbot certonly --webroot -w /var/www/example -d xxx.net -d www.xxx.net
```
/etc/nginx/sites-enabled/default
```
listen 443 ssl http2;
ssl_protocols TLSv1 TLSv1.1 TLSv1.2 TLSv1.3;
ssl_ciphers TLS-CHACHA20-POLY1305-SHA256:TLS-AES-256-GCM-SHA384:TLS-AES-128-GCM-SHA256:HIGH:!aNULL:!MD5;
ssl_prefer_server_ciphers on;
ssl_certificate /etc/letsencrypt/live/xxx.net/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/xxx.net/privkey.pem;
ssl_trusted_certificate /etc/letsencrypt/live/xxx.net/chain.pem;
```

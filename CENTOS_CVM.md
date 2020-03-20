**CentOS笔记（CentOS 8.1 + Nginx 1.14 + Apache 2.4 + MariaDB 10.3 + PHP 7.2 + Tomcat 9.0 + Nodejs 12）**
```
yum install mariadb-server
systemctl start mariadb.service
systemctl enable mariadb.service
mysql_secure_installation
```
```
yum install httpd
yum install php php-mysqlnd php-gd php-pdo php-mbstring php-mcrypt
systemctl start httpd.service
systemctl enable httpd.service
```
/etc/httpd/conf/httpd.conf
```
Listen 81
ServerAdmin xxx@live.cn
ServerName xxx.net
AllowOverride All
Directory index.php index.html
```
/etc/php.ini
```
expose_php = Off
date.timezone = Asia/Shanghai
```
~~/etc/phpMyAdmin/config.inc.php~~
```
$cfg['Servers'][$i]['auth_type'] = 'http';
```
```
yum install java-11-openjdk-devel tomcat tomcat-webapps tomcat-admin-webapps mysql-connector-java
cp /usr/share/java/mysql-connector-java.jar /usr/share/tomcat/lib/
systemctl start tomcat.service
systemctl enable tomcat.service
```
/usr/share/tomcat/conf/tomcat.conf
```
JAVA_HOME="/usr/lib/jvm/java-11-openjdk-1.8.0.151-1.b12.el7_4.x86_64"
```
/usr/share/tomcat/conf/tomcat-users.xml
```
<role rolename="admin-gui"/>
<role rolename="manager-gui"/>
<user username="admin" password="xxx" roles="admin-gui,manager-gui" />
```
/usr/share/tomcat/conf/server.xml
```
<Connector port="8080" address="127.0.0.1" protocol="HTTP/1.1" connectionTimeout="20000"
 redirectPort="8443"/>
<Context path="" docBase="ROOT" debug="0" reloadable="true"/>     #在<Host>节点里面添加
```
```
rpm -Uvh https://nginx.org/packages/centos/8/x86_64/RPMS/nginx-1.16.1-1.el8.ngx.x86_64.rpm
yum install nginx
systemctl start nginx.service
systemctl enable nginx.service
```
```
yum install epel-release
yum install certbot
certbot certonly --webroot -w /var/www/xxx.net -d xxx.net -m xxx@live.cn --agree-tos
```
/etc/nginx/conf.d/default.conf
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
            proxy_read_timeout 300s;
            proxy_pass http://java;
            proxy_redirect off;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        }
    }
    server {
        listen 443 ssl http2;
        server_name xxx.net;
        ssl_protocols TLSv1.1 TLSv1.2;
        ssl_ciphers HIGH:!aNULL:!MD5;
        ssl_prefer_server_ciphers on;
        ssl_certificate /etc/letsencrypt/live/xxx.net/fullchain.pem;
        ssl_certificate_key /etc/letsencrypt/live/xxx.net/privkey.pem;
        ssl_trusted_certificate /etc/letsencrypt/live/xxx.net/chain.pem;
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
            #proxy_set_header Host $host;
            #proxy_set_header Host $host:443;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        }
    }
}
```
```
curl -sL https://rpm.nodesource.com/setup_12.x | sudo bash -
yum install nodejs
```

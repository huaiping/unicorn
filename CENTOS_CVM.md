**CentOS笔记（CentOS 7.4 + Nginx 1.12 + Apache 2.4 + MariaDB 5.5 + PHP 5.4 + Tomcat 7.0 + Nodejs 12）**
```
yum install mariadb-server
systemctl start mariadb.service
systemctl enable mariadb.service
mysql_secure_installation
```
```
yum install httpd
yum install php php-mysql php-gd php-pdo php-mbstring php-mcrypt phpmyadmin
systemctl start httpd.service
systemctl enable httpd.service
```
/etc/httpd/conf/httpd.conf
```
 42 Listen 81
 86 ServerAdmin xxx@live.cn
 95 ServerName xxx.net
151 AllowOverride All
164 Directory index.php index.html
```
/etc/php.ini
```
375 expose_php = Off
878 date.timezone = Asia/Shanghai
```
~~/etc/phpMyAdmin/config.inc.php~~
```
$cfg['Servers'][$i]['auth_type'] = 'http';
```
```
yum install java-1.8.0-openjdk tomcat tomcat-webapps tomcat-admin-webapps mysql-connector-java
cp /usr/share/java/mysql-connector-java.jar /usr/share/tomcat/lib/
systemctl start tomcat.service
systemctl enable tomcat.service
```
/usr/share/tomcat/conf/tomcat.conf
```
JAVA_HOME="/usr/lib/jvm/java-1.8.0-openjdk-1.8.0.151-1.b12.el7_4.x86_64"
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
rpm -Uvh https://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm
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
        location /phpMyAdmin/ {
            proxy_pass http://php;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        }
    }
    server {
        listen 443 ssl http2;
        server_name xxx.net;
        ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
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
```
systemctl stop firewalld.service 
systemctl disable firewalld.service
```
/etc/selinux/config
```
#SELINUX=enforcing
#SELINUXTYPE=targeted
SELINUX=disabled
```
```
setenforce 0
```

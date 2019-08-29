**LNAMP笔记（Debian 7.6 + Nginx 1.2 + Apache 2.2 + MySQL 5.5 + PHP 5.4 + Tomcat 7.0 + Python 3.4）**

/etc/apt/sources.list
```
deb http://mirrors.aliyun.com/debian/            wheezy                   main  contrib  non-free
deb http://mirrors.aliyun.com/debian/            wheezy-proposed-updates  main  contrib  non-free
deb http://mirrors.aliyun.com/debian-security/   wheezy/updates           main  contrib  non-free
```
```
apt-get update
apt-get upgrade
apt-get install mysql-server mysql-client
apt-get install openjdk-7-jdk tomcat7 tomcat7-admin tomcat7-docs tomcat7-examples
apt-get install apache2 php5 libapache2-mod-php5 libapache2-mod-rpaf libapache2-mod-jk libmysql-java
 php5-gd php5-mysql php5-mcrypt php5-memcached phpmyadmin libapache2-mod-wsgi-py3 python3-pip
cp /usr/share/java/mysql-connector-java-5.1.32-bin.jar /usr/share/tomcat7/lib/
```
```
mysql_secure_installation        /etc/apache2/ports.conf       /etc/apache2/mods-enable/rpaf.conf
/etc/mysql/my.cnf                NameVirtualHost *:81          RPAheader X-Forwarded-For
bind-address = 127.0.0.1         Listen 127.0.0.1:81
```
/etc/tomcat7/tomcat-users.xml
```
<role rolename="admin-gui"/>
<role rolename="manager-gui"/>
<user username="admin" password="xxx" roles="admin-gui,manager-gui"/>
```
/etc/tomcat7/server.xml
```
<Connector port="8080" address="127.0.0.1" protocol="HTTP/1.1" connectionTimeout="20000"
 redirectPort="8443"/>
<Connector port="8009" protocol="AJP1.3" redirectPort="8443"/>        #取消注释
<Context path="" docBase="/var/www" debug="0" reloadable="true"/>     #在<Host>节点里面添加
```
/etc/libapache2-mod-jk/workers.properties #修改以下3行，默认配置还是tomcat6的
```
workers.tomcat_home=/usr/share/tomcat7
workers.java_home=/usr/lib/jvm/java-7-openjdk-amd64
worker.myworker.host=localhost
```
/etc/apache2/sites-available/default
```
ServerName 127.0.0.1                                /etc/apache2/conf.d/security
<VirtualHost *:81>                                  ServerTokens	Prod
    ServerAdmin xxx@xxx.net                         ServerSignature	Off
    DocumentRoot /var/www
    …                                               /etc/php5/apache2/php.ini
</VirtualHost>                                      expose_php = off
                                                    date.timezone = Asia/Shanghai
AllowOverride All                                   upload_max_filesize = 10M

                                                    /etc/nginx/nginx.conf
                                                    server_tokens = off

#在</VirtualHost>前添加：
JkMount	/servlet/* ajp13_worker             #访问servlet .jsp .do .action等文件时才挂载jk模块
JkMount /*.jsp ajp13_worker
JkUnMount /*.php ajp13_worker
```
```
apt-get install nginx
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
/etc/nginx/sites-available/default #SSL证书用FileZilla SFTP协议上传，配置类似
```
server {                                                upstream php {
    listen 80;                                              server 127.0.0.1:81;
    root /var/www;                                      }
    index index.html index.htm;                         upstream java {
    server_name localhost;                                  server 127.0.0.1:8080;
    proxy_redirect http://dev.xxx.net:81/ /;            }
    location ~ \.php$ {                                 server {               #禁止通过ip访问
        proxy_pass http://127.0.0.1:81;                     server_name _;
        include proxy_params;                               return 404;
    }                                                   }
    location ~ .*.jsp$ {                                server {
        index index.jsp;                                    listen 80;
        proxy_pass http://127.0.0.1:8080;                   root /var/www;
        include proxy_params;                               index index.php index.html index.htm;
    }                                                       server_name php.xxx.net;
    location ~* (.*)\.(jpg|gif|png|html|js|css)$ {          proxy_redirect http://php.xxx.net:81/ /;
        root /data/web/html;                                location / {
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

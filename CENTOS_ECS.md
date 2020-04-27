**CentOS笔记（CentOS 8.1 + Nginx 1.14 + Apache 2.4 + MariaDB 10.3 + PHP 7.2 + Tomcat 9.0 + Python 3.6）**
```
timedatectl set-timezone Asia/Shanghai
dnf makecache
dnf update
```
```
dnf install mariadb-server
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
dnf install httpd
dnf install php php-cli php-fpm php-gd php-json php-mbstring php-mysqlnd php-pdo php-xml php-zip
systemctl start httpd.service
systemctl enable httpd.service
```
```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```
/etc/httpd/conf/httpd.conf
```
Listen 81
ServerAdmin xxx@live.cn
ServerName xxx.net
AllowOverride All
DirectoryIndex index.php index.html
```
/etc/php.ini
```
expose_php = Off
date.timezone = Asia/Shanghai
```
/etc/http/conf.d/ssl.conf
```
#Listen 443
```
```
wget https://files.phpmyadmin.net/phpMyAdmin/5.0.2/phpMyAdmin-5.0.2-all-languages.tar.gz
tar -zxvf phpMyAdmin-5.0.2-all-languages.tar.gz
mv phpMyAdmin-5.0.2-all-languages /var/www/html/phpmyadmin
mv /var/www/html/phpmyadmin/config.sample.inc.php /var/www/html/phpmyadmin/config.inc.php
```
/var/www/html/phpmyadmin/config.inc.php
```
$cfg['blowfish_secret'] = 'QzW4Zpi0GD5NPztqkBi{Y0=auzLCCjuW';
```
```
mysql < /var/www/html/phpmyadmin/sql/create_tables.sql -u root -p
mkdir /var/www/html/phpmyadmin/tmp
chown -R apache:apache /var/www/html/phpmyadmin
chmod 777 /var/www/html/phpmyadmin/tmp
```
```
dnf install java-11-openjdk-devel
groupadd --system tomcat
useradd -d /usr/share/tomcat -r -s /bin/false -g tomcat tomcat
```
```
wget https://mirrors.aliyun.com/apache/tomcat/tomcat-9/v9.0.34/bin/apache-tomcat-9.0.34.tar.gz
tar -zxvf apache-tomcat-9.0.34.tar.gz
mv apache-tomcat-9.0.34 /usr/share/tomcat

wget https://downloads.mariadb.com/Connectors/java/connector-java-2.6.0/mariadb-java-client-2.6.0.jar
mv mariadb-java-client-2.6.0.jar /usr/share/tomcat/lib

chown -R tomcat:tomcat /usr/share/tomcat
chmod +x /usr/share/tomcat/bin/*.sh
```
/etc/systemd/system/tomcat.service
```
[Unit]
Description=Tomcat Server
After=syslog.target network.target

[Service]
Type=forking
User=tomcat
Group=tomcat

Environment=JAVA_HOME=/usr/lib/jvm/java-11-openjdk-11.0.6.10-0.el8_1.x86_64
Environment='JAVA_OPTS=-Djava.awt.headless=true'

Environment=CATALINA_HOME=/usr/share/tomcat
Environment=CATALINA_BASE=/usr/share/tomcat
Environment=CATALINA_PID=/usr/share/tomcat/temp/tomcat.pid
Environment='CATALINA_OPTS=-Xms512M -Xmx1024M'

ExecStart=/usr/share/tomcat/bin/catalina.sh start
ExecStop=/usr/share/tomcat/bin/catalina.sh stop

[Install]
WantedBy=multi-user.target
```
```
systemctl daemon-reload
systemctl start tomcat.service
systemctl enable tomcat.service
```
/usr/share/tomcat/conf/tomcat-users.xml
```
<role rolename="admin-gui"/>
<role rolename="manager-gui"/>
<user username="admin" password="xxx" roles="admin-gui,manager-gui"/>
```
/usr/share/tomcat/conf/server.xml
```
<Connector port="8080" address="127.0.0.1" protocol="HTTP/1.1" connectionTimeout="20000"
 redirectPort="8443"/>
<Context path="" docBase="ROOT" debug="0" reloadable="true"/>     #在<Host>节点里面添加
```
```
dnf install nginx
systemctl start nginx.service
systemctl enable nginx.service
```
```
dnf install httpd python3 python3-pip mod_wsgi gcc httpd-devel python3-devel mariadb-devel
pip3 install --upgrade pip
```
```
wget https://dl.eff.org/certbot-auto
mv certbot-auto /usr/local/bin/certbot
chown root /usr/local/bin/certbot
chmod 0755 /usr/local/bin/certbot
/usr/local/bin/certbot certonly --webroot -w /usr/share/nginx/html -d xxx.net -m x@live.cn --agree-tos
```
```
certbot renew --dry-run
crontab -e
30 2 * * 1 /usr/local/bin/certbot renew  >> /var/log/le-renew.log
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

        location /phpmyadmin/ {
            proxy_pass http://php;
            proxy_set_header Host $host:443;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        }
    }
```

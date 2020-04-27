**openSUSE笔记（openSUSE 15.1 + Nginx 1.14 + Apache 2.4 + MariaDB 10.2 + PHP 7.2 + Tomcat 9.0 + Python 3.6）**
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
zypper install php7 php7-mysql php7-gd php7-mbstring apache2-mod_php7 phpMyAdmin
a2enmod php7
systemctl restart apache2.service

mysql < /usr/share/doc/packages/phpMyAdmin/sql/create_tables.sql -u root -p
```
```
zypper install java-11-openjdk tomcat
systemctl start tomcat.service
systemctl enable tomcat.service
/srv/tomcat/webapps/
```
/usr/share/tomcat/conf/server.xml
```
<Connector port="8080" protocol="HTTP/1.1" connectionTimeout="20000" redirectPort="8443"/>
<Context path="" docBase="ROOT" debug="0" reloadable="true"/>     #在<Host>节点里面添加
```
```
zypper install python3 python3-pip python3-setuptools python3-wheel

pip3 install --upgrade pip
pip3 uninstall configobj

zypper install python3-certbot
certbot certonly --webroot -w /srv/www/htdocs -d xxx.net -m x@live.cn --agree-tos
```
```
zypper install nginx
systemctl start nginx.service
systemctl enable nginx.service
```

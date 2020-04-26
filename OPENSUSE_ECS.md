**openSUSE笔记（openSUSE 15.1 + Nginx 1.14 + Apache 2.4 + MariaDB 10.2 + PHP 7.2 + Tomcat 9.0 + Python 3.6）**
```
zypper refresh
zypper update
zypper dup
```
```
zypper mr -da
zypper ar -fc https://mirrors.aliyun.com/opensuse/distribution/leap/15.1/repo/oss openSUSE-Aliyun-OSS
zypper ar -fc https://mirrors.aliyun.com/opensuse/distribution/leap/15.1/repo/non-oss openSUSE-Aliyun-NON-OSS
zypper ar -fc https://mirrors.aliyun.com/opensuse/update/leap/15.1/oss openSUSE-Aliyun-UPDATE-OSS
zypper ar -fc https://mirrors.aliyun.com/opensuse/update/leap/15.1/non-oss openSUSE-Aliyun-UPDATE-NON-OSS
zypper ref
```
```
解决 zypper: symbol lookup error: /usr/lib64/libproxy.so.1: undefined symbol
rpm -i --force http://mirrors.aliyun.com/opensuse/distribution/openSUSE-stable/repo/oss/x86_64/libmodman1-2.0.1-lp151.2.3.x86_64.rpm
```
```
zypper install mariadb mariadb-client
systemctl start mariadb.service
systemctl enable mariadb.service

mysql_secure_installation
```
```
zypper install apache2
systemctl start apache2.service
systemctl enable apache2.service
/srv/www/htdocs/
```
```
zypper install php php-mysql php-gd php-mbstring apache2-mod_php7 phpMyAdmin
a2enmod php7
systemctl restart apache2.service
```
```
zypper install openjdk
wget https://mirrors.aliyun.com/apache/tomcat/tomcat-9/v9.0.34/bin/apache-tomcat-9.0.34.tar.gz
tar -zxvf apache-tomcat-9.0.34.tar.gz
```
```
zypper install python3-pip python3-setuptools python3-wheel
```
```
zypper in nginx
systemctl start nginx.service
systemctl enable nginx.service
mkdir -p /etc/nginx/ssl/
```

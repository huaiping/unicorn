**Python笔记（Django 3.1.3）**

Debian 10.6 + Python 3.7.3
```
apt install apache2 libapache2-mod-wsgi-py3 python3-pip mysql-server libmariadbd-dev
pip3 install --upgrade pip
pip3 install Django
pip3 install mysqlclient
```
CentOS 8.2.2004 + Python 3.6.8
```
yum install epel-release
yum install httpd python3-pip mariadb-server gcc httpd-devel python3-devel mariadb-devel
pip3 install mod_wsgi django mysqlclient
```
/etc/pip.conf
```
[global]
trusted-host = mirrors.aliyun.com
index-url = https://mirrors.aliyun.com/pypi/simple
extra-index-url = https://pypi.tuna.tsinghua.edu.cn/simple
```
/etc/apache2/sites-available/000-default.conf
```
<VirtualHost *:80>
    ServerName xxx.net
    WSGIScriptAlias / /var/www/demo/django.wsgi
    <Directory "/var/www/demo">
        Options FollowSymLinks Indexes
        AllowOverride all
        Require all granted
    </Directory>

    Alias /robots.txt /var/www/demo/static/robots.txt
    Alias /static /var/www/demo/static
    <Location "/static">
        SetHandler None
    </Location>
    <Directory "/var/www/demo/static">
        Require all granted
    </Directory>

    ErrorLog "/var/log/apache2/py-error.log"
    CustomLog "/var/log/apache2/py-access.log" combined
</VirtualHost>
```
```
django-admin.py startproject demo
cd demo
```
demo/django.wsgi
```
# -*- coding: utf-8 -*-

import os
import sys
import django.core.handlers.wsgi

os.environ['DJANGO_SETTINGS_MODULE'] = 'demo.settings'
app_path = "/var/www/demo/"
sys.path.append(app_path)

from django.core.wsgi import get_wsgi_application
application = get_wsgi_application()
```
demo/setting.py
```
LANGUAGE_CODE = 'zh_CN'
TIME_ZONE = 'Asia/Shanghai'

INSTALLED_APPS = ()注册模块

DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.mysql',
        'NAME': 'qdm160638220_db',
        'USER': 'qdm160638220',
        'PASSWORD': '',
        'HOST': 'qdm160638220.my3w.com',
        'PORT': '3306',
        'OPTIONS': {
            'autocommit': True,
        }
    }
}

STATIC_ROOT = os.path.join(BASE_DIR,'static')
```
```
python3 manage.py startapp guestbook
python3 manage.py check
python3 manage.py makemigrations
python3 manage.py migrate
python3 manage.py syncdb
python3 manage.py collectstatic
python3 manage.py createsuperuser
python3 manage.py runserver
```
```
pip3 --version
pip3 install --upgrade pip
pip3 search Django
pip3 list --outdated
```

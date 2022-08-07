**Ruby笔记（Debian 11.4 + Ruby 2.5.5 + Rails 7.0）**
```
apt install curl build-essential ruby2.5 ruby-dev libmariadbclient-dev zlib1g-dev libxml2-dev
apt install apache2 mysql-server libapache2-mod-passenger
curl -sL http://deb.nodesource.com/setup_16.x | sudo bash -
apt install nodejs

mysql_secure_installation
mysql -u root -p
MariaDB>grant all privileges on *.* to 'user123'@'%' Identified by 'pass123';

gem sources --add https://mirrors.aliyun.com/rubygems/ --remove https://rubygems.org/
gem update --system
gem install bundler
bundle config mirror.https://rubygems.org https://mirrors.aliyun.com/rubygems/

chown www-data:www-data -R /var/www/demo
cd /var/www/demo
bundle install
rake db:create
```
/etc/apache2/mods-available/passenger.conf
```
<IfModule mod_passenger.c>
    PassengerRoot /usr/lib/ruby/vendor_ruby/phusion_passenger/locations.ini
    PassengerDefaultRuby /usr/bin/ruby
    PassengerDefaultUser www-data
</IfModule>
```
/etc/apache2/sites-available/000-default.conf
```
DocumentRoot /var/www/demo/public
<Directory /var/www/demo/public>
    RailsEnv development
    Options -MultiViews
    AllowOverride All
    Require all granted
    RailsBaseURI /demo
    #PassengerResolveSymlinksInDocumentRoot on
</Directory>
```
gem 常用命令
```
gem sources -l            # 列出更新源
gem list                  # 列出已经安装的包
gem update                # 更新所有包
gem update --system       # 更新Gem自身
gem install xxx           # 安装包
gem uninstall xxx         # 卸载安装包
gem cleanup               # 清除cache
gem help                  # 帮助
```
Rails in Docker
```
docker pull debian:latest
docker run --name cynthia -i -t -p 3000:3000 -v /home/data:/data debian:latest /bin/bash
apt install curl build-essential ruby2.5 ruby-dev libmariadb-dev libxml2-dev zlib1g-dev
curl -sL http://deb.nodesource.com/setup_16.x | bash -
apt install nodejs
gem sources --add https://gems.ruby-china.com/ --remove https://rubygems.org/
gem install mysql2 bundler rails
bundle config mirror.https://rubygems.org https://gems.ruby-china.com
cd demo
bundle install
bundle lock --add-platform x86-mingw32 x86-mswin32 x64-mingw32 java
rails server

rake db:migrate RAILS_ENV=production
rake generate_secret_token

EDITOR="nano --wait" bin/rails credentials:edit
```
Rails
```
rails new demo -d mysql
cd demo
rails generate controller Blog index
rails server -p 80       # 启动server, 由http://localhost:80/访问,默认3000端口
vim app/controllers/blog_controller.rb
加入
def index
  render :text => "Hello world"
end
```

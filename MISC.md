**Miscellaneous（Debian 10.1）**

Sudo 1.8.27
```
visudo
username ALL=(ALL) ALL
```
Fcitx 4.2.9.6
```
sudo dpkg-reconfigure locales
sudo apt install fcitx fcitx-sunpinyin fcitx-config-gtk2 fcitx-ui-classic
sudo apt install bash-completion fonts-wqy-microhei
sudo reboot
configure中添加sunpinyin
```
```
sudo apt install intel-microcode
sudo apt install firmware-realtek
sudo apt install nvidia-driver
```
```
sed -i 's/stretch/buster/g' /etc/apt/sources.list
```
Git 2.20.1
```
apt install git

ssh-keygen -t rsa -b 4096 -C "xxx@xxx.cn"
cat ~/.ssh/id_rsa.pub
把所有字符粘贴到github的SSH Key输入框。

git config --global user.email "xxx@xxx.cn"
git config --global user.name "xxx"
git config --global push.default simple
git config --global credential.helper store
```
```
gpg --gen-key
gpg --list-keys
gpg --armor --export <Pub_key_ID>
把GPG key 加到github帐号
git config --global user.signingkey <Pub_key_ID>
git config --global commit.gpgsign true
```
```
sudo rm /var/cache/apt/archives/lock
sudo rm /var/lib/dpkg/lock
```
Vue
```
npm install -g @vue/cli
vue --version
vue create demo
```
phpMyAdmin 4.9.1
```
mkdir /usr/share/phpmyadmin
mkdir /etc/phpmyadmin
mkdir -p /var/lib/phpmyadmin/tmp
chown -R www-data:www-data /var/lib/phpmyadmin
touch /etc/phpmyadmin/htpasswd.setup
cd /tmp
wget https://files.phpmyadmin.net/phpMyAdmin/4.9.1/phpMyAdmin-4.9.1-all-languages.tar.gz
tar xfz phpMyAdmin-4.9.1-all-languages.tar.gz
mv phpMyAdmin-4.9.1-all-languages/* /usr/share/phpmyadmin/
rm phpMyAdmin-4.9.1-all-languages.tar.gz
rm -rf phpMyAdmin-4.9.1-all-languages
cp /usr/share/phpmyadmin/config.sample.inc.php  /usr/share/phpmyadmin/config.inc.php
```
Composer 1.9.0 + Laravel 6.0
```
sudo apt install curl php-cli php-gd php-mbstring php-mysql php-xml
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo composer create-project --prefer-dist laravel/laravel demo
php artisan serve
```
```
composer -V
composer self-update
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```
```
sudo dd if=x.iso of=/dev/sdb bs=4M; sync

sudo umount /dev/sdb1
sudo mkfs.vfat -I /dev/sdb
```
```
apt install flameshot
```
```
/etc/inputrc
set bell-style none

sudo rmmod pcspkr    #临时关闭beep
sudo echo "blacklist pcspkr" >> /etc/modprobe.d/blacklist    #永久关闭beep
modprobe pcspkr      #加载驱动
```
git撤销commit
```
git reset --hard <commit_id>
git push origin HEAD --force
```
```
https://mirrors.cloud.tencent.com
https://mirrors.163.com
https://mirrors.ustc.edu.cn
https://mirrors.tuna.tsinghua.edu.cn
```
```
ssh-keygen -t rsa
ls /root/.ssh/
ssh root@REMOTE_SERVER mkdir -p .ssh
cat /root/.ssh/id_rsa.pub | ssh root@REMOTE_SERVER 'cat >> /root/.ssh/authorized_keys'
ssh root@REMOTE_SERVER "chmod 700 .ssh; chmod 600 .ssh/authorized_keys"
nano /etc/ssh/sshd_config
RSAAuthentication yes
PubkeyAuthentication yes
AuthorizedKeysFile      %h/.ssh/authorized_keys
/etc/init.d/sshd restart
```
PHP and MSSQL on Ubuntu
```
apt install php5-sybase
```
Certbot 0.31.0
```
sudo certbot renew --dry-run
crontab -e
30 2 * * 1 /usr/bin/certbot renew  >> /var/log/le-renew.log
```
Redis 5.0.3
```
sudo apt install redis-server
openssl rand 60 | openssl base64 -A

sudo nano /etc/redis/redis.conf
bind 127.0.0.1
supervised systemd
requirepass xxxxxxxxxxxxxxxxxxx

sudo systemctl restart redis.service
sudo systemctl status redis
```
```
帐号权限
useradd test     #centos会在home下新增同名目录 ubuntu需添加-m
useradd test -m

passwd test
gpasswd -a test sudo
su test
```
backup.sh
sh backup.sh    #执行
```
#!/bin/sh

backUpFolder=/home/xxx/backup
date_now=`date +%Y_%m_%d_%H%M`
backFileName=mall_$date_now

cd $backUpFolder
mkdir -p $backFileName

mongodump -h 127.0.0.1:27017 -d mall -u mall -p 123 -o $backFileName

tar zcvf $backFileName.tar.gz $backFileName
rm -rf $backFileName
```
.NET Core 3.0
```
wget -qO- https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > microsoft.asc.gpg
sudo mv microsoft.asc.gpg /etc/apt/trusted.gpg.d/
wget -q https://packages.microsoft.com/config/debian/10/prod.list
sudo mv prod.list /etc/apt/sources.list.d/microsoft-prod.list
sudo chown root:root /etc/apt/trusted.gpg.d/microsoft.asc.gpg
sudo chown root:root /etc/apt/sources.list.d/microsoft-prod.list
sudo apt update
sudo apt install apt-transport-https
sudo apt install dotnet-sdk-3.0
```
CentOS 7.7.1908 minimal
```
yum install epel-release
yum upgrade
yum -y groupinstall "X Window System" "Fonts"
 yum -y groupinstall "Cinnamon"
yum install lightdm
 yum install cinnamon
yum --enablerepo=epel -y install cinnamon*
yum install gnome-terminal
systemctl set-default graphical.target
echo "exec /usr/bin/cinnamon-session" >> ~/.xinitrc
startx

yum install ntfs-3g
grub2-mkconfig -o /boot/grub2/grub.cfg

yum install fcitx*
yum install im-chooser
$ imsettings-switch fcitx
注销

https://www.google.cn/chrome
yum install redhat-lsb-core liberation-fonts
rpm -ivh google-chrome-stable.rpm

rpm -Uvh http://li.nux.ro/download/nux/dextop/el7/x86_64/nux-dextop-release-0-5.el7.nux.noarch.rpm
yum install vlc
Tools -> Preferences -> Subtitles/OSD 修改编码和字体

https://code.visualstudio.com/docs/setup/linux
sudo rpm --import https://packages.microsoft.com/keys/microsoft.asc
sudo ...
yum check-update
sudo yum install code

rpm -ivh http://mirrors.whsir.com/centos/7/x86_64/RPM/rar-5.7.1-1.el7.x86_64.rpm
yum install rar

sudo yum install filezilla shutter
```

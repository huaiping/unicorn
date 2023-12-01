**Miscellaneous（Debian 12.2）**

Sudo 1.9.5
```
usermod -aG sudo bob 或 gpasswd -a bob sudo

visudo
username ALL=(ALL) ALL
```
Fcitx 4.2.9.8
```
sudo dpkg-reconfigure locales
sudo apt install fcitx fcitx-sunpinyin fcitx-config-gtk fcitx-ui-classic
sudo apt install wget bash-completion fonts-wqy-microhei
sudo reboot
configure中添加sunpinyin
```
```
apt install intel-microcode firmware-realtek nvidia-driver
```
```
sed -i 's/stretch/buster/g' /etc/apt/sources.list

sed -i 's/15.1/15.5/g' /etc/zypp/repos.d/*.repo
```
```
https://www.microsoft.com/en-us/download/details.aspx?id=49117
https://config.office.com/deploymentsettings

https://github.com/massgravel/Microsoft-Activation-Scripts/releases
irm https://massgrave.dev/get | iex

https://github.com/zbezj/HEU_KMS_Activator/releases

https://github.com/2dust/v2rayN/releases

https://github.com/adobe-fonts/source-han-sans/releases
```
```
https://ssl-config.mozilla.org
```
```
pwgen -s 14 5 或 pwgen -cnys 14 5

openssl rand -base64 14

gpg2 --gen-random --armor 1 14
```
Git 2.30.2
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

git clone git@github.com:huaiping/unicorn.git
```
Read the Docs
```
pip3 install sphinx sphinx_rtd_theme recommonmark
mkdir docs
cd docs
sphinx-quickstart

source/conf.py
extensions = ['recommonmark']
html_theme = "sphinx_rtd_theme"

make clean
make html
```

Vue
```
npm install -g @vue/cli
vue --version
vue create demo
```
Composer 2.6.5 + Laravel 9.x
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
apt install vlc filezilla flameshot
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
Redis 6.0.16
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

usermod -aG wheel username
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

**Node.js笔记（Node.js 14.17.0 + npm 6.14.13）**

Debian 10.9
```
apt install curl apt-transport-https gnupg2 lsb-release
curl -sL https://deb.nodesource.com/setup_14.x | sudo bash -
apt install nodejs
```
CentOS 8.4
```
curl -sL https://rpm.nodesource.com/setup_14.x | sudo bash -
dnf install nodejs
```
Express
```
npm config set registry https://registry.npm.taobao.org
npm install -g express-generator
express -e demo
cd demo
npm install ejs --save
npm install express --save
npm install mysql --save
npm start
```
Supervisor
```
sudo npm install -g supervisor
supervisor app.js
```
PM2
```
npm install -g pm2
pm2 start ./bin/www
```
```
pm2 start app.js            # 启动app.js应用程序
pm2 start app.js --watch    # 当文件变化时自动重启应用
pm2 list                    # 列表PM2启动的所有的应用程序
pm2 monit                   # 显示每个应用程序的CPU和内存占用情况
pm2 show [app-name]         # 显示应用程序的所有信息
pm2 logs                    # 显示所有应用程序的日志
pm2 stop all                # 停止所有的应用程序
pm2 stop 0                  # 停止id为0的指定应用程序
pm2 restart all             # 重启所有应用
pm2 delete all              # 关闭并删除所有应用
pm2 delete 0                # 删除指定应用id 0
pm2 startup                 # 创建开机自启动命令
pm2 save
```
Grunt
```
npm install -g grunt-cli
cd demo
npm init
npm install grunt --save-dev
npm install grunt-contrib-cssmin --save-dev
npm install grunt-contrib-uglify --save-dev
```
npm常用命令
```
npm -v                      # 显示版本，检查npm是否正确安装
npm update -g npm           # 升级npm模块
npm install express         # 安装express模块
npm install -g express      # 全局安装express模块
npm list                    # 列出已安装模块
npm show express            # 显示模块详情
npm update                  # 升级当前目录下的项目的所有模块
npm update express          # 升级当前目录下的项目的指定模块
npm update -g express       # 升级全局安装的express模块
npm uninstall express       # 删除指定的模块
```
```
npm cache clean -f
npm install -g n
n stable 或 sudo n 14.17.0
```

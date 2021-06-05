**MongoDB笔记（MongoDB 4.4）**

Debian 10.9
```
https://docs.mongodb.com/manual/administration/install-on-linux/
wget -qO - https://www.mongodb.org/static/pgp/server-4.4.asc | sudo apt-key add -
```
/etc/apt/sources.list.d/mongodb-org-4.4.list
```
deb https://repo.mongodb.org/apt/debian buster/mongodb-org/4.4 main
```
```
deb https://mirrors.aliyun.com/mongodb/apt/debian
```
```
apt update
apt install mongodb-org
service mongod start
systemctl enable mongod
systemctl status mongod
```
CentOS 8.4

/etc/yum.repos.d/mongodb-org-4.4.repo
```
[mongodb-org-4.4]
name=MongoDB Repository
baseurl=https://repo.mongodb.org/yum/redhat/$releasever/mongodb-org/4.4/x86_64/
gpgcheck=1
enabled=1
gpgkey=https://www.mongodb.org/static/pgp/server-4.4.asc
```
```
dnf install mongodb-org
```
/etc/mongod.conf
```
security:
  authorization: enabled
```
```
systemctl restart mongod
```
```
mongo
use admin
db.createUser(
  {
    user: "mongoAdmin",
    pwd: "changeMe",
    roles: [ { role: "userAdminAnyDatabase", db: "admin" } ]
  }
)
quit()
mongo -u mongoAdmin -p --authenticationDatabase admin
```

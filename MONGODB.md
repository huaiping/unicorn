**MongoDB笔记（MongoDB 4.2）**

Debian 10.1
```
https://docs.mongodb.com/manual/administration/install-on-linux/
wget -qO - https://www.mongodb.org/static/pgp/server-4.2.asc | sudo apt-key add -
```
/etc/apt/sources.list.d/mongodb-org-4.2.list
```
deb https://repo.mongodb.org/apt/debian stretch/mongodb-org/4.2 main
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
CentOS 7.7.1908

/etc/yum.repos.d/mongodb-org-4.2.repo
```
[mongodb-org-4.2]
name=MongoDB Repository
baseurl=https://repo.mongodb.org/yum/redhat/$releasever/mongodb-org/4.2/x86_64/
gpgcheck=1
enabled=1
gpgkey=https://www.mongodb.org/static/pgp/server-4.2.asc
```
```
yum install mongodb-org
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

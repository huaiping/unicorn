**MongoDB笔记（Debian 12.2 + MongoDB 7.0）**
```
https://docs.mongodb.com/manual/administration/install-on-linux/
wget -qO - https://www.mongodb.org/static/pgp/server-7.0.asc | sudo apt-key add -
```
/etc/apt/sources.list.d/mongodb-org-7.0.list
```
deb https://repo.mongodb.org/apt/debian bookworm/mongodb-org/7.0 main
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

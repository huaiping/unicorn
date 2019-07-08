**MongoDB笔记（Debian 10.0 + MongoDB 4.0.10）**
```
sudo apt install curl
curl https://www.mongodb.org/static/pgp/server-4.0.asc | sudo apt-key add -
```
/etc/apt/sources.list.d/mongodb-org-4.0.list
```
deb https://repo.mongodb.org/apt/debian stretch/mongodb-org/4.0 main
```
```
deb https://mirrors.tuna.tsinghua.edu.cn/mongodb/apt/debian
```
```
sudo apt update
sudo apt install mongodb-org
sudo systemctl enable mongod
sudo systemctl start mongod
sudo systemctl status mongod
```
/etc/mongod.conf
```
security:
  authorization: enabled
```
```
sudo systemctl restart mongod
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

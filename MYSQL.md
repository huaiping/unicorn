**MySQL笔记（Debian 10.0 + Docker 19.03.1 + MySQL 5.7）**
```
docker pull mysql/mysql-server:5.7

mkdir -pv /root/docker/mysql/data
mkdir -pv /root/docker/mysql/master
mkdir -pv /root/docker/mysql/slave
```
./master/master.cnf
```
[mysqld]
log-bin=mysql-bin
server-id=101
```
./slave/slave.cnf
```
[mysqld]
log-bin=mysql-bin
server-id=102
```
```
docker create --name master -v /root/docker/mysql/data/master:/var/lib/mysql -v /root/docker/mysql/master:/etc/mysql/conf.d -e MYSQL_ROOT_PASSWORD=123456 -p 3306:3306 mysql:5.7

docker create --name slave -v /root/docker/mysql/data/slave:/var/lib/mysql -v /root/docker/mysql/slave:/etc/mysql/conf.d -e MYSQL_ROOT_PASSWORD=123456 -p 3316:3306 mysql:5.7

docker start master
docker start slave 
```
```
mysql> GRANT REPLICATION SLAVE ON *.* to 'user'@'%' identified by 'mysql';
mysql> show master status;
```

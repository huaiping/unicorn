**MySQL笔记（Debian 10.4 + Docker 19.03.10 + MySQL 5.7）**
```
docker pull mysql:5.7
docker run -p 3339:3306 --name mysql-master -e MYSQL_ROOT_PASSWORD=123456 -d mysql:5.7
docker run -p 3340:3306 --name mysql-slave -e MYSQL_ROOT_PASSWORD=123456 -d mysql:5.7
```
配置Master
```
docker exec -it mysql-master /bin/bash
```
/etc/mysql/my.cnf
```
[mysqld]
server-id=100
log-bin=mysql-bin
```
```
service mysql restart
docker start mysql-master
docker exec -it mysql-master /bin/bash

mysql -u root -p
mysql> grant replication slave, replication client on *.* to 'slave'@'%' identified by '123456';
mysql> flush privileges;
mysql> show master status;
```
配置Slave
```
docker exec -it mysql-slave /bin/bash
```
/etc/mysql/my.cnf
```
[mysqld]
server-id=101
log-bin=mysql-slave-bin
relay_log=mysql-relay-bin
read-only=1
```
```
service mysql restart
docker start mysql-slave
docker exec -it mysql-slave /bin/bash

mysql -u root -p
mysql> stop slave;
mysql> change master to master_host='172.17.0.2', master_user='slave', master_password='123456',
 master_port=3306, master_log_file='mysql-bin.000001', master_log_pos=617, master_connect_retry=10;
mysql> start slave;
mysql> show slave status \G;
检查 Slave_IO_Running 和 Slave_SQL_Running
```
```
查master的ip
docker inspect --format='{{.NetworkSettings.IPAddress}}' mysql-master
```
```
时间不一致的问题
dpkg-reconfigure tzdata
Asia Shanghai
```

**MySQL笔记（Debian 10.0 + Docker 19.03.1 + MySQL 5.7.27）**
```
docker pull mysql:5.7
docker run -p 3339:3306 --name mysql-master -e MYSQL_ROOT_PASSWORD=123456 -d mysql:5.7
docker run -p 3340:3306 --name mysql-slave -e MYSQL_ROOT_PASSWORD=123456 -d mysql:5.7
docker ps
```
配置Master
```
docker exec -it mysql-master /bin/bash
```
nano /etc/mysql/my.cnf
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
mysql> CREATE USER 'slave'@'%' IDENTIFIED BY '123456';
mysql> GRANT REPLICATION SLAVE, REPLICATION CLIENT ON *.* TO 'slave'@'%';
```
配置Slave
```
docker exec -it mysql-slave /bin/bash
```
nano /etc/mysql/my.cnf
```
[mysqld]
server-id=101
log-bin=mysql-slave-bin
relay_log=edu-mysql-relay-bin
```
```
service mysql restart
docker start mysql-master
```
链接Master和Slave
```
主mysql> show master status;
记住 File 和 Position 的值

查master的ip
docker inspect --format='{{.NetworkSettings.IPAddress}}' mysql-master
```
```
docker exec -it mysql-slave /bin/bash
mysql -u root -p
mysql> change master to master_host='172.17.0.2', master_user='slave', master_password='123456',
 master_port=3306, master_log_file='mysql-bin.000001', master_log_pos=617, master_connect_retry=30;

检查 Slave IO Running 和 Slave SQL Running
mysql> show slave status \G;
mysql> start slave;
mysql> show slave status \G;
```

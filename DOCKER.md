**Docker笔记（Docker 19.03.13-ce）**

Debian 10.5
```
apt remove docker docker-engine docker.io containerd runc
apt update
apt install apt-transport-https ca-certificates curl gnupg2 software-properties-common
curl -fsSL https://download.docker.com/linux/debian/gpg | sudo apt-key add -
apt-key fingerprint 0EBFCD88
add-apt-repository \
    "deb [arch=amd64] https://download.docker.com/linux/debian $(lsb_release -cs) stable"
apt update
apt install docker-ce docker-ce-cli containerd.io
```
```
https://mirrors.aliyun.com/docker-ce/linux/debian
```
CentOS 7.7.1908
```
yum remove docker docker-client docker-client-latest docker-common docker-latest \
    docker-latest-logrotate docker-logrotate docker-engine
yum install yum-utils device-mapper-persistent-data lvm2
yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
yum install docker-ce docker-ce-cli containerd.io
systemctl start docker.service
systemctl enable docker.service
```
```
docker pull debian:latest
docker run --name test01 -i -t -p 9090:80 -v /home/data:/data debian:latest /bin/bash

docker start test01
docker attach test01

docker:/# apt update
docker:/# apt install nano
docker:/# nano /etc/apt/sources.list
docker:/# apt update
docker:/# apt install nginx
docker:/# [Ctrl+p][Ctrl+q]
```
Docker常用命令
```
docker images                            # 列出所有镜像
docker ps                                # 列出正在运行的容器
docker ps -a                             # 列出所有的容器
docker pull centos                       # 下载centos镜像
docker top <container>                   # 查看容器内部运行程序

docker stop <container>                  # 停止一个正在运行的容器
docker start <container>                 # 启动一个已经停止的容器
docker restart <container>               # 重启容器
docker rm <container>                    # 删除容器

docker exec -it <container> /bin/bash    # 进入ubuntu类容器的bash
docker run -i -t centos /bin/bash        # 运行centos镜像
docker rmi <image-id>                    # 删除镜像
docker search httpd                      # 查找Hub上的httpd镜像
docker commit 8a7db469d429 xxxx
```
```
docker pull registry.docker-cn.com/library/ubuntu:16.04
docker pull hub.c.163.com/library/tomcat:latest

docker run --name master -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root -d mysql:5.7
docker run --name wordpress --link master:mysql -p 88:80 -v /data:/var/lib/mysql -d wordpress

docker run --name t2 -tdi --privileged=true -p 8989:80 centos:latest /usr/sbin/init
```

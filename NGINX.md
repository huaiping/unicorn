**Nginx学习笔记（CentOS 7.6 + Nginx 1.16 + Keepalived 1.3.5）**
```
yum install openssl-devel popt-devel ipvsadm libnl*
yum install keepalived
```
/etc/keepalived/keepalived.conf
```
主服务器配置 192.168.70.169
global_defs {
  notification_email {
    acassen@firewall.loc   //没有配置服务器邮箱，可以去掉
    failover@firewall.loc
    sysadmin@firewall.loc
  }
  notification_email_from Alexandre.Cassen@firewall.loc
  smtp_server 192.168.200.1
  smtp_connect_timeout 30
  router_id LVS_DEVEL
}

vrrp_instance VI_1 {
  state MASTER            //双机热备（主）
  interface eth4          //选择网络（用ip add 查看网络）
  virtual_router_id 51    //和(副机)一样
  priority 100            //主机选100(副机要低于100)
  advert_int 1
  authentication {
    auth_type PASS
    auth_pass 1111        //密码（主副要保持一致）
  }
  virtual_ipaddress {
    192.168.70.84         //虚拟ip
  }
}
```
```
副服务器配置  192.168.70.170
global_defs {
  notification_email {
    acassen@firewall.loc
    failover@firewall.loc
    sysadmin@firewall.loc
  }
  notification_email_from Alexandre.Cassen@firewall.loc
  smtp_server 192.168.200.1
  smtp_connect_timeout 30
  router_id LVS_DEVEL
}

vrrp_instance VI_1 {
  state BACKUP
  interface eth4
  virtual_router_id 51
  priority 50
  advert_int 1
  authentication {
    auth_type PASS
    auth_pass 1111
  }
  virtual_ipaddress {
    192.168.70.84
  }
}
```
```
service keepalived start 或者 systemctl start keepalived
```

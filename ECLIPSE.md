**Eclipse Platform笔记（Debian 9.9 + Eclipse 4.12）**
```
sudo apt install git openjdk-8-jdk tomcat8
```
```
Eclipse IDE for Enterprise Java Developers
https://www.eclipse.org/downloads/packages/
https://mirrors.ustc.edu.cn/eclipse/technology/epp/downloads/release/2019-06/R/
```
Git配置
```
ssh-keygen -t rsa -b 4096 -C "xxx@xxx.cn"
cat ~/.ssh/id_rsa.pub
把所有字符粘贴到github的SSH Key输入框

git config --global user.email "xxx@xxx.cn"
git config --global user.name "xxx"
git config --global push.default simple
git config --global credential.helper store
```
Eclipse配置
```
cd /usr/share/tomcat8
sudo ln -s /var/lib/tomcat8/conf conf
sudo ln -s /etc/tomcat8/policy.d/03catalina.policy conf/catalina.policy
sudo ln -s /var/log/tomcat8 log
sudo chmod -R 777 /usr/share/tomcat8/conf
```
```
File -> New -> Project -> Web -> Dynamic Web Project
New Runtime -- Apache Tomcat v8.5 -- Tomcat installation directory -- /usr/share/tomcat8

导入项目后，在项目名上点右键，Build Path -> Configure Build Path
右边 Library标签，Add Library -> Server Runtime -> Apache Tomcat 8.5
```
```
Window -- Preferences -- Maven -- Archetypes -- Add Remote Catalog
Catalog File：http://repo1.maven.org/maven2/archetype-catalog.xml
            或http://uk.maven.org/maven2/archetype-catalog.xml
Description：maven catalog

Window -- Preferences -- Java -- Installed JREs -- Execution Environments
Java-SE1.8选中右侧java-8-openjdk-amd64[perfect match]
```
解决Cannot create a server using the selected type的问题：
```
cd .metadata/.plugins/org.eclipse.core.runtime/.settings/
rm org.eclipse.jst.server.tomcat.core.prefs
rm org.eclipse.wst.server.core.prefs
```
解决Could not load the Tomcat server configuration at /usr/share/tomcat8/conf. The configuration may be corrupt or incomplete /usr/share/tomcat8/conf/catalina.policy (No such file or directory)的问题：
```
cd /usr/share/tomcat8
sudo ln -s /var/lib/tomcat8/conf conf
sudo ln -s /etc/tomcat8/policy.d/03catalina.policy conf/catalina.policy
sudo ln -s /var/log/tomcat8 log
sudo chmod -R 777 /usr/share/tomcat8/conf
```
Struts2.5框架
```
在项目的WebContent/WEB-INF/web.xml中</web-app>之前添加如下配置：
<filter>
    <filter-name>action2</filter-name>
    <filter-class>
      org.apache.struts2.dispatcher.filter.StrutsPrepareAndExecuteFilter
    </filter-class>
</filter>
<filter-mapping>
    <filter-name>action2</filter-name>
    <url-pattern>/*</url-pattern>
</filter-mapping>
```

# Install Oracle Instant Client and OCI8
Oracle Instant Client is one way to connect to Oracle and the PHP Extension OCI8 makes it possible to connect from PHP to Oracle with Oracle Instant Client.
This is a brief description on how to install them on CentOS 7. The described method uses only precompiled packages provided by CentOS, Oracle and PECL. If you want to compile PHP this document is not for you.

## Prerequisites
Before you do this you need to install Apache. If you are planning to run Oracle on the same machine install Oracle before as well. There are plenty of information on the web.

## Get Oracle Instant Client
Download from http://www.oracle.com/technetwork/topics/linuxx86-64soft-092277.html if you have CentOS7 64 bit

For other platforms look here http://www.oracle.com/technetwork/database/features/instant-client/index-097480.html

You need both basic and devel, at the time of writing this they are called
```
oracle-instantclient12.2-basic-12.2.0.1.0-1.x86_64.rpm
oracle-instantclient12.2-devel-12.2.0.1.0-1.x86_64.rpm
```
Note: basiclite won’t work and make sure you get 64- or 32-bit version according to your OS

## Update and install required package from CentOS
```
yum update
yum install gcc prelink php php-devel php-pear
pear upgrade pear
```

## Install Oracle Instant Client
```
rpm -Uvh oracle-instantclient12.2-basic-12.2.0.1.0-1.x86_64.rpm
rpm -Uvh oracle-instantclient12.2-devel-12.2.0.1.0-1.x86_64.rpm
```
### Tell SELinux to accept the client
```
chcon -t textrel_shlib_t /usr/lib/oracle/12.2/client64/lib/*.so
execstack -c /usr/lib/oracle/12.2/client64/lib/*.so.*
setsebool -P httpd_execmem 1
setsebool -P httpd_can_network_connect on
```
### Set the path to client into ld.so.conf
```
echo /usr/lib/oracle/12.2/client64/lib > /etc/ld.so.conf.d/oracle-instantclient
```

## Install the PHP Extension OCI8 using PECL
The normal command is 'pecl install oci8' but that will install the latest version which is not compatible with the PHP version that comes with CentOS 7 so it is necessary to specify the version
You can read more here: https://pecl.php.net/package/oci8

Note: during the installation you will be asked for a path, this is the answer: instantclient,/usr/lib/oracle/12.2/client64/lib
```
pecl install oci8-2.0.12
```

Add the extension to the PHP configuration and restart Apache
```
echo extension=oci8.so > /etc/php.d/oci8.ini

apachectl restart
```

### Check if oci8 is loaded
```
[root@ghost ~]# php -m|grep oci
oci8
```
If that returns oci8 you're it is working. You can also use

&lt;?php print_r(get_loaded_extensions()); ?&gt;

## Resources
This document was compiled with info found at
* https://github.com/NatLibFi/NDL-VuFind2/wiki/Oracle-with-PHP-5.6-or-PHP-7-on-RHEL-6-or-CentOS-7
* https://vufind.org/wiki/installation:php_oci
* http://www.oracle.com/technetwork/articles/dsl/technote-php-instant-12c-2088811.html
* http://www.emsperformance.net/2013/12/20/connecting-to-oracle-database-from-php-with-oci8/

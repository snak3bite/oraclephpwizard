# Install Oracle Instant Client and OCI8 on debian (11) bullseye x86-64 (64-bit) 
Oracle Instant Client is one way to connect to Oracle and the PHP Extension OCI8 makes it possible to connect from PHP to Oracle with Oracle Instant Client.
This is a brief description on how to install them on debian bullseye. The described method uses only precompiled packages provided by debian, Oracle and PECL. If you want to compile PHP this document is not for you.

## Prerequisites
Before you begin you need to install Apache and PHP.
```
apt install php libapache2-mod-php
```

If you are planning to run Oracle on the same machine install Oracle before as well. There is plenty of information on the web.

## Install required packages from debian
```
apt install php-dev php-pear build-essential libaio1
```

Make a dir for Oracle software and get in there
```
mkdir -p /opt/oracle && cd /opt/oracle
```

## Get Oracle Instant Client, note that those are 64-bit
Download from https://www.oracle.com/database/technologies/instant-client/linux-x86-64-downloads.html
It is not nessesary to log in anymore

This document assumes you download version 19.18 (supports Oracle 11.2 and higher). If you pick another version, you need to adjust paths etc below accordingly.

You need both basic and sdk
```
wget https://download.oracle.com/otn_software/linux/instantclient/1918000/instantclient-basic-linux.x64-19.18.0.0.0dbru.zip
wget
https://download.oracle.com/otn_software/linux/instantclient/1918000/instantclient-sdk-linux.x64-19.18.0.0.0dbru.zip
```

## Unzip instantclient
Make sure that they are located in /opt/oracle else move them there
```
unzip instantclient-basic-linux.x64-19.18.0.0.0dbru.zip
unzip instantclient-sdk-linux.x64-19.18.0.0.0dbru.zip
```

## Set the path to client into ld.so.conf and update the runtime link path
If you have the Oracle database on the same machine you might be better off using the LD_LIBRARY_PATH environment variable
```
echo /opt/oracle/instantclient_19_18 > /etc/ld.so.conf.d/oracle-instantclient.conf
ldconfig
```

## Install the PHP Extension OCI8 using PECL
The normal command is 'pecl install oci8' but that will install the latest version which is not compatible with the PHP version that comes with debian bullseye, so it is necessary to specify the version
You can read more here: https://pecl.php.net/package/oci8

Note: during the installation you will be asked for a path, this is the answer: instantclient,/opt/oracle/instantclient_19_18
```
pecl install oci8-2.2.0
```

Add the extension to the PHP available mods, enable the mod and restart Apache
```
echo "extension=oci8.so" >> /etc/php/7.4/mods-available/oci8.ini
phpenmod oci8
systemctl restart apache2
```

### Check if oci8 is loaded
```
[root@ghost ~]# php -m|grep oci
oci8
```
If that returns oci8 you're it is working. You can also use

&lt;?php print_r(get_loaded_extensions()); ?&gt;


# Install Oracle PHP Wizard
Installing is a matter of copying some files to your web server and compile a package in your oracle database

## Caution
OPW is not intended to use on a public available server. For your own good only install and use in your isolated development environment!

## Prerequisites
* Oracle database (so far opw is tested with 11g R2 Express but any version will probably do, atleast 8 and higher)
* Web server with PHP (so far tested on CentOS 7 with Apache 2.4 and PHP 5.4)
* Oracle Instant Client (tested with version 12.2)
* Download OPW https://github.com/snak3bite/oraclephpwizard/releases

## In oracle
Compile pck_opw_procinfo.sql. Make sure that you connect with the same user that owns the packages and procedures that you want to create code for. You can for example do this in SQL Developer or in SQLPlus. In the latter you would do: 
```
SQL> @pck_opw_procinfo.sql

Package created.

Package body created.

SQL> 
```
or
```
SQL > @/the/path/pck_opw_procinfo.sql
```

## In Apache
Installing and configure Apache and PHP is outside the scope of this document. The web is full of tutorials ...
Note: If using CentOS remember SELinux is always happier when files are copied to the directories for Apache and not moved, i.e. dont use mv.

You need all php-files in the php folder

Put all of them in the same directory and chmod and chown them as suiteable for your server.
Lets say this is /var/www/html/opw

If it is a default install of Apache this will do
```
[root@ghost ~]# mkdir /var/www/html/opw
[root@ghost ~]# chmod 775 /var/www/html/opw
[root@ghost ~]# cp *php /var/www/html/opw
[root@ghost ~]# chmod 664 /var/www/html/opw/*php
```


### Edit config.php
Most important are the first lines, that's the info needed for opw to connect to Oracle
```
define( "DB_USER", "dbopw" );
define( "DB_PASSWORD", "opwxc1#.04-deKDOo139jdOD" );
define( "DB_CONNSTR", "localhost/XE" );
```

Next up is ORACLE_OBJECT_OWNER. You only need to change this if the owner of the procedures that you want to create code for is a different user that connects to the database.

If you for example compile procedures with user 'dbadmin' and let OPW connect to Oracle with 'dbopw', then this is how you do it
```
define( "DB_USER", "dbopw" );
...
// The owner of packages and procedures
define( "ORACLE_OBJECT_OWNER", 'dbadmin' );
```
The only thing OPW does in your database is connect and execute the stored procedures in package PCK_OPW_PROCINFO so you might 
very well only let 'dbopw' just have those privileges and nothing more. Like this:
```
CREATE USER dbopw IDENTIFIED BY dbopw DEFAULT TABLESPACE USERS;
GRANT CONNECT TO dbopw;

GRANT EXECUTE ON PCK_OPW_PROCINFO TO dbopw;
```

If you want OPW to save the class-files to the file system you need to specify a directory where Apache have write privileges.
```
// Path to save files to
define( "REPO", "/var/www/html/rw" );
```
The rest of the file can be left as is.

### Set up a directory where Apache can write files
On a server without SELinux this task is straight forward but on server with SELinux, like CentOS, it is a little bit tricky.
This is one way to do it (if SELinux is on):
```
[root@ghost ~]# mkdir /var/www/html/rw
[root@ghost ~]# chown apache /var/www/html/rw
[root@ghost ~]# chmod 775 /var/www/html/rw
[root@ghost ~]# chcon -t httpd_sys_rw_content_t /var/www/html/rw
[root@ghost ~]# ls -Z /var/www/html/
drwxrwxr-x. apache    root      unconfined_u:object_r:httpd_sys_rw_content_t:s0 rw
```
(ls -Z is very handy)

### Get Prisma js
Prisma makes the code on the web page look nice. It is not necessary, it just looks nicer.

If you want Prisma open http://prismjs.com/download.html

Make sure you select Minimized Version and unselect everything but PHP (Core and C-like is ticked automatically). Download both prisma.js and prisma.css to the same place where you have the php-files (/var/www/html/opw)
(or edit config.php accordingly).

Note: I have experienced problems with the page and it sometimes fails to build the source correctly. Make sure that the preview box for prisma.js have a row that starts with Prism.languages.php (there should be about 4 rows in total).

### Done
open http://localhost/opw/opw.php and hopefully it works :)

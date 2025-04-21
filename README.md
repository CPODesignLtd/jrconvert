# FSConvertPHP

## Export database to a sqldump file

Requires a mysql dump installed. Expected mysql installed

_note: MySQL is installed as part of installation of xampp-win32-1.7.2.exe (in install folder)_

```bash
cd C:\Program Files (x86)\xamp\mysql\bin

mysqldump -P 3306 -h fssoftware.brn.savvy.cz -u savvy_mhdspoje -p savvy_mhdspoje > d:\fssoftdb.sql
```

## SQL import

Requires access to mysqlimport that is installed in same directory as mysqldump. We assume that this runs using docker:.

```
execute docker-compose -d --force-recreate

mysqlimport.exe -h 172.0.0.1 -u user -p savvy_mhdspoje D:\fssoftdb.sql
```

## Importing large files into mysql

1. Install xamp with mysql
2. Enable openssl in php ini (an extension to uncomment)
3. Login into mysql-phpadmin
4. Default credetials are: root without password
5. Add mysql bin directory to environment variables
   1. Open system properties
   1. Open Environment Variables
   1. In system variables locate PATH variable name
   1. Click edit
   1. A new `Edit environment variable` windows pops up
   1. Click new
   1. Add path to bin directory of mysql (in my case: C:\xampp\mysql\bin)
   1. Click ok to close this window
   1. Click ok to close Environment Variables
   1. Close system properties
6. Open a command line in location of sql files to import (this has to be a new window)
7. Type the command to login into mysql

```bash
mysql -h localhost -u root
```

8. Import individual files using the script

```bash
source 01.savvy_mhdspoje_testtable.sql
```

9. Once completed, refresh mysql-phpAdmin and all tables will be created as defined in the script.

## Docker

commands used

```shell
docker-compose up --force-recreate
```

```shell
docker-compose down
```

### docker compose - configuration

### mapping a single file to seed data

```docker
volumes:
    - ../DB/savvy_mhdspoje_testtable.sql:/docker-entrypoint-initdb.d/savvy_mhdspoje_testtable.sql
```

### mapping of a directory to seed data

```docker
volumes:
    - ../DB:/docker-entrypoint-initdb.d
```

# php8 conversion

## VS Code extensions

- Github Markdown Preview
- PHP IntelliSense

## VS Code configuration php linting

configuration for php exe should be as follows:

```
"php.validate.executablePath": "c:/xampp/php/php.exe",
```

source: https://code.visualstudio.com/docs/languages/php

## Installation of xdebug

1. Copy the "install/php8/php_xdebug-3.0.4-8.0-vs16-x86_64.dll" into local installation of php to ext directory (in my case c:\xamp\php\ext).
2. Update php.ini file with the configuration

```
zend_extension = php_xdebug-3.0.4-8.0-vs16-x86_64.dll

[xdebug]
xdebug.mode = debug
xdebug.start_with_request = yes
xdebug.idekey = VSCODE
xdebug.client_port = 9003
xdebug.client_host = "127.0.0.1"
xdebug.discover_client_host  = 1
xdebug.log="/tmp/xdebug.log"
xdebug.cli_color = 1
```

3. restart server
4. phpinfo() should display "xdebug" section

## Update size of the application

1. Update php.ini file to allow bigger files upload using the following code (update the value as required)

```
upload_max_filesize = 1000M;
post_max_size = 1000M;
```

## Issues to resolve

- password in db not encrypted
- php info should require authentication accessible on: http://mhdspoje.cz/prepareadmin/info.php
- stripslashes increases issue of SQL injection (https://stackoverflow.com/questions/10054484/effectiveness-of-stripslashes-against-sql-injection)
- database table types (https://stackoverflow.com/questions/12614541/whats-the-difference-between-myisam-and-innodb)
  -MyISAM does not have referencial integrity
  -InnoDB supports referential integrity

### Unused functions

#### get_magic_quotes_gpc

- depreciated https://www.php.net/manual/en/function.get-magic-quotes-gpc.php

### Remove redundant files

- jrvfs.css file is empty and possibly can be removed

## dependencies

### XAMP document mapping

the source code stored in locaiton:

```
'C:/Code/Clients/FS/FSConvertPHP/convertedphp8'
```

for this configuration we need to update 'httpd.config' for apache. update document root and directory to set it for localhost

```
# <Directory "C:/xampp/htdocs">

#
DocumentRoot "C:/Code/Clients/FS/FSConvertPHP/convertedphp8"
<Directory "C:/Code/Clients/FS/FSConvertPHP/convertedphp8">
    #
    # Possible values for the Options directive are "None", "All",
    # or any combination of:
    #   Indexes Includes FollowSymLinks SymLinksifOwnerMatch ExecCGI MultiViews
    #
    # Note that "MultiViews" must be named *explicitly* --- "Options All"
    # doesn't give it to you.
    #
    # The Options directive is both complicated and important.  Please see
    # http://httpd.apache.org/docs/2.4/mod/core.html#options
    # for more information.
    #
    Options Indexes FollowSymLinks Includes ExecCGI

    #
    # AllowOverride controls what directives may be placed in .htaccess files.
    # It can be "All", "None", or any combination of the keywords:
    #   AllowOverride FileInfo AuthConfig Limit
    #
    AllowOverride All

    #
    # Controls who can get stuff from this server.
    #
    Require all granted
</Directory>
```

### mbstring not present in phpinfo

update the php.ini file and enable extensions if not present.

```
;extension_dir = "ext"
```

to

```
extension_dir = "ext"
```

source: https://stackoverflow.com/questions/30047169/phpmyadmin-error-the-mbstring-extension-is-missing-please-check-your-php-confi

### PHP 8 Debugging in VS CODE

php.ini

```
zend_extension = C:\xampp\php\ext\php_xdebug-3.0.4-8.0-vs16-x86_64.dll

[xdebug]

xdebug.mode = debug
xdebug.discover_client_host = 1
xdebug.start_with_request = yes
xdebug.client_port = 9000
```

launch.json file

```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type": "php",
      "request": "launch",
      "port": 9000
    },
    {
      "name": "Launch currently open script",
      "type": "php",
      "request": "launch",
      "program": "${file}",
      "cwd": "${fileDirname}",
      "port": 9000
    }
  ]
}
```

VS settings manual config path

```
"php.validate.executablePath": "C:\\xampp\\php\\php.exe",
```

# Packages Import/Export

## Import packages

data pro import jsou ulozene v /prepareadmin/Data/

## Export packages

Data pro export pro mobilni applikaci jsou pod /Data/

## Deleting data from db

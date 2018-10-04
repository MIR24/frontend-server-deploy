

Nodejs, npm, gulp, acl, deployer.phar must be installed.

php70-memcached must be installed.
Memcached must be installed and served.

Edit .env file if needed.
It will be propageted to the shared folder while config:clone task.

Cp mysql dump into root folder of this deploy project, than configure filename at hosts.yml e.g.:
```
localhost:
    dumpfile: mir24.sql
```

Create database via mysql console command:
```
mysql> create database mir24_7 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Than run:
```
$ git clone git@github.com:MIR24/frontend-server-deploy.git
$ dep deploy test

git clone command may lag at 'update_code' task due to unknown reasons.

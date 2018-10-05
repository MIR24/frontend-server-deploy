
## Requirements
`Nodejs`, `npm`, Linux `acl`, [deployer](https://deployer.org/docs/installation) must be installed.

`php*-memcached` must be installed.<br>
`Memcached` and `mysql-server` must be installed and served.

## Start

Create database via `mysql` console command:
```mysql
mysql> CREATE DATABASE mir24_7 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```
Clone deploy project:
```
$ git clone git@github.com:MIR24/frontend-server-deploy.git /home/www/dev7.mir24.tv/frontend-server-deploy
```
Configure `deploy_path` at `hosts.yml`.<br>
If you going to deploy test bench it could be `/home/www/dev7.mir24.tv/frontend-server-deploy`

Edit `.env` file if needed (e.g. to configure DB connection).<br>
It will be propageted to the shared folder while `config:clone` task.

Download initial dump file (you can get example dump file [here](https://drive.google.com/open?id=1L2vvkscPZYIWjAU8QA_TtN3wbay4Yi3A)).<br>
`Cp` mysql dump into root folder of this deploy project:
```
$ cp /tmp/mir24_7.sql /home/www/dev7.mir24.tv/frontend-server-deploy/
```
Configure filename at `hosts.yml` e.g.:
```yml
localhost:
    dumpfile: mir24.sql
```
Initial project structure looks like this:<br>
![Deploy procedure](https://raw.githubusercontent.com/MIR24/frontend-server-deploy/master/images/deploy_procedure_3.png "Deploy procedure")

Now run:
```
$ cd /home/www/dev7.mir24.tv/frontend-server-deploy
$ dep deploy test
```
## Tips
Use `--branch` option to deploy specific branch:
```
$ dep deploy test --branch=develop
```

Use `dep` verbose to examine deploy procedure:
```
$ dep deploy test -v
$ dep deploy test -vv
$ dep deploy test -vvv
```


>**`git clone` command may lag at `update_code` task due to still unknown reasons.**
________


You can cancel deploy at the sql dump executing stage to prevent unwelcome DB drop:

![Deploy procedure](https://raw.githubusercontent.com/MIR24/frontend-server-deploy/master/images/deploy_procedure_2.png "Deploy procedure")

________

Normally complete deploy procedure should looks like this:

![Deploy procedure](https://raw.githubusercontent.com/MIR24/frontend-server-deploy/master/images/deploy_procedure.png "Deploy procedure")

Run `dep artisan:key:generate test` if `APP_KEY` in `shared/.env` still empty even after deploy complete.

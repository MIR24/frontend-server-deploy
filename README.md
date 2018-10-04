
## Requirements
`Nodejs`, `npm`, Linux `acl`, [deployer](https://deployer.org/docs/installation) must be installed.

`php70-memcached` must be installed.<br>
`Memcached` must be installed and served.

## Start
Edit `.env` file if needed.<br>
It will be propageted to the shared folder while `config:clone` task.

`Cp` mysql dump into root folder of this deploy project, than configure filename at `hosts.yml` e.g.:
```yml
localhost:
    dumpfile: mir24.sql
```

Create database via `mysql` console command:
```mysql
mysql> CREATE DATABASE mir24_7 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Than run:
```
$ git clone git@github.com:MIR24/frontend-server-deploy.git
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

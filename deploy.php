<?php
namespace Deployer;

require 'recipe/laravel.php';

inventory('hosts.yml');

// Project name
set('application', 'mir24-frontend-server');

// Project repository
set('repository', 'git@github.com:MIR24/frontend-server.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

set('bin/npm', function () {
    return run('which npm');
});

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', ['public/uploads']);

// Tasks

// TODO Include npm.php recipe instead of writing task
desc('Install npm packages');
task('npm:install', function () {
    if (has('previous_release')) {
        if (test('[ -d {{previous_release}}/node_modules ]')) {
            run('cp -R {{previous_release}}/node_modules {{release_path}}');
        }
    }
    run("cd {{release_path}} && {{bin/npm}} install", ["timeout" => 1800]);
});

task('build', function () {
    run('cd {{release_path}} && gulp');
});

//TODO configure database as subrepo
desc('Cloning database');
task('db:clone', function () {
    run('cd {{release_path}} && git clone git@github.com:MIR24/database.git');
});

//TODO maybe better path procedure for shared dir
desc('Propagate configuration file');
task('config:clone', function () {
    if(test('[ -w {{deploy_path}}/shared/.env ]'))
    {
        writeln('<info>Config file already shared, check and edit shared_folder/.env</info>');
    } else {
        run('cp {{deploy_path}}/.env {{deploy_path}}/shared/.env');
    }
});

desc('Uploading initial dump may took a minute');
task('db:init', function () {
    writeln('<info>Check if {{deploy_path}}/{{dumpfile}} exists</info>');
    if(test('[ ! -r {{deploy_path}}/{{dumpfile}} ]')) {
        writeln('<error>DB dump file not found, upload file, than configure hosts</error>');
        writeln('<comment>Stop deployment</comment>');
        invoke('deploy:unlock');
        die;
    }
    if(askConfirmation("Going to overwrite existing database, confirm..", false))
        run('cd {{deploy_path}} && mysql -h{{dbhost}} -u{{dbuser}} -p{{dbpass}} mir24_7 < mir24_7.sql');
    else{
        writeln('<comment>Stop deployment</comment>');
        invoke('deploy:unlock');
        die;
    }
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
after('deploy:update_code', 'db:clone');
after('deploy:vendors', 'npm:install');
after('deploy:vendors', 'build');
after('deploy:lock', 'db:init');

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');


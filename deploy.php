<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', '');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);




set('rsync',[
    'exclude'       => ['excludes_file'],
    'exclude-file'  => '/tmp/localdeploys/excludes_file', //Use absolute path to avoid possible rsync problems
    'include'       => [],
    'include-file'  => false,
    'filter'        => [],
    'filter-file'   => false,
    'filter-perdir' => false,
    'flags'         => 'nrzcE', // Recursive, with compress, check based on checksum rather than time/size, preserve Executable flag
//    'options'       => ['delete', 'delete-after', 'force'], //Delete after successful transfer, delete even if deleted dir is not empty
    'timeout'       => 3600, //for those huge repos or crappy connection
]);
set('rsync_src', __DIR__);
set('rsync_dest','{{release_path}}');

// Hosts
host('simpleitsolutions.ch')
    ->stage('production')
    ->user('simpleit')
//    ->set('deploy_path', '{{deploy_path}}')
    ->set('deploy_path', '/home/simpleit/ch.simpleitsolutions.fz-backend')
    ->set('rsync_src', __DIR__)
    ->set('rsync_dest','{{deploy_path}}')
    ;



// Tasks

task('deploy', [
//    'deploy:prepare',
//    'deploy:release',
    'rsync',
//    'deploy:vendors',
//    'deploy:symlink',
//    'cleanup',
])->desc('Deploy your project');

task('mytest', [
    'pwd',
    'cd',
    'pwd',
]);

task('pwd', function () {
    $result = run('pwd');
    writeln("Current dir: $result");
});

task('cd', function () {
    run('cd /home/simpleit/ch.simpleitsolutions.fz-backend');
//    writeln("Current dir: $result - {{deploy_path}}");
});

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');


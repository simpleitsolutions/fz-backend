<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\CustomDeployer;

return new class extends CustomDeployer
{
    private $deployDir = '/home/simpleit/ch.simpleitsolutions.fz-backend';

    public function configure()
    {
        return $this->getConfigBuilder()
            // SSH connection string to connect to the remote server (format: user@host-or-IP:port-number)
            ->server('simpleit@simpleitsolutions.ch')
            ->symfonyEnvironment('prod')
            // the absolute path of the remote server directory where the project is deployed
//            ->deployDir('')
            // the URL of the Git repository where the project code is hosted
//            ->repositoryUrl('https://github.com/symfony/symfony-demo')
            // the repository branch to deploy
//            ->repositoryBranch('master')
        ;
    }

    // run some local or remote commands before the deployment is started
    public function beforeStartingDeploy()
    {
        // $this->runLocal('./vendor/bin/simple-phpunit');
    }

    // run some local or remote commands after the deployment is finished
    public function beforeFinishingDeploy()
    {
        // $this->runRemote('{{ console_bin }} app:my-task-name');
        // $this->runLocal('say "The deployment has finished."');
    }

    public function deploy()
    {
        $server = $this->getServers()->findAll()[0];

//        $this->runRemote('cp app/Resources/views/maintenance.html web/maintenance.html');
        $this->runLocal(sprintf('rsync --progress -crDpLt --force --delete ./ %s@%s:%s', $server->getUser(), $server->getHost(), $this->deployDir));
        $this->runRemote('SYMFONY_ENV=prod sudo -u www-data bin/console cache:warmup --no-debug');
        $this->runRemote('SYMFONY_ENV=prod sudo -u www-data bin/console app:update-contents --no-debug');
//        $this->runRemote('rm -rf web/maintenance.html');
    }

    public function cancelDeploy()
    {
        // ...
    }

    public function rollback()
    {
        // ...
    }

};

<?php

namespace Qafoo\TimePlannerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BootstrapCommand extends ContainerAwareCommand
{
    /**
     * Default users
     *
     * @var array
     */
    protected $defaultUsers = array('manuel', 'kore', 'toby', 'benjamin');

    protected function configure()
    {
        $this
            ->setName('timeplanner:bootstrap')
            ->setDescription('Bootstrap aapplication with default users')
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Force re-creation of database. Deletes all documents.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $couchDbConnection = $this->getContainer()->get('doctrine_couchdb.client.default_connection');

        if ($input->getOption('force')) {
            try {
                $output->writeln("Drop database.");
                $couchDbConnection->getHttpClient()->request('DELETE', '/' . $couchDbConnection->getDatabase());
            } catch (\Doctrine\CouchDB\HTTP\HTTPException $e) {
                // Just ignore
            }
        }

        try {
            $couchDbConnection->getDatabaseInfo($couchDbConnection->getDatabase());
            return;
        } catch (\Doctrine\CouchDB\HTTP\HTTPException $e) {
            // This is expected, we create stuff in this case
        }

        $output->writeln("Create database.");
        $couchDbConnection->getHttpClient()->request('PUT', '/' . $couchDbConnection->getDatabase());

        $userManipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
        foreach ($this->defaultUsers as $user) {
            $output->writeln(" * Create user: $user");
            $userManipulator->create($user, 'test', "$user@qafoo.com", true, false);
        }
    }
}

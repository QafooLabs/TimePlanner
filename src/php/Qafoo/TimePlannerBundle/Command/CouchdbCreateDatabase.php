<?php

namespace Qafoo\TimePlannerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CouchdbCreateDatabaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('couchdb:database:create')
            ->setDescription('Create CouchDB database')
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
    }
}

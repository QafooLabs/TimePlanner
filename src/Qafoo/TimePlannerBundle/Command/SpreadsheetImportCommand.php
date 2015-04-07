<?php

namespace Qafoo\TimePlannerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SpreadsheetImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('timeplanner:time-planning:import')
            ->setDescription('Import Google spreadsheet CSV with time planning')
            ->addArgument(
                'month',
                InputArgument::REQUIRED,
                'Month to import to, in format <YYYY-MM>'
            )->addArgument(
                'url',
                InputArgument::REQUIRED,
                'URL of the CSV ; Example: ' .
                'http://docs.google.com/feeds/download/spreadsheets/Export?key=<key>&exportFormat=csv&gid=<gid>'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jobImporter = $this->getContainer()->get('qafoo.time_planner.domain.job_csv_importer');
        $jobs = $jobImporter->import($input->getArgument('month'), $input->getArgument('url'));

        $output->writeln("Imported:");
        foreach ($jobs as $job) {
            $output->writeln(
                sprintf(
                    " - %s: %s",
                    $job->customer,
                    $job->project
                )
            );
        }
    }
}

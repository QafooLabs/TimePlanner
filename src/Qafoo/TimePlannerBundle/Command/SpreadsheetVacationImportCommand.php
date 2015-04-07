<?php

namespace Qafoo\TimePlannerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SpreadsheetVacationImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('timeplanner:vacation:import')
            ->setDescription('Import Google spreadsheet CSV with vacations')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'URL of the CSV ; Example: ' .
                'http://docs.google.com/feeds/download/spreadsheets/Export?key=<key>&exportFormat=csv&gid=<gid>'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jobImporter = $this->getContainer()->get('qafoo.time_planner.domain.vacation_csv_importer');
        $vacations = $jobImporter->import(
            $input->getArgument('url'),
            array(
                'Kore' => 'kore',
                'Manuel' => 'mapi',
                'Benjamin' => 'benjamin',
                'Toby' => 'toby',
            )
        );

        $output->writeln("Imported:");
        foreach ($vacations as $vacation) {
            $output->writeln(
                sprintf(
                    " - %s: %s to %s",
                    $vacation->user->login,
                    $vacation->start->format('j. F'),
                    $vacation->end->format('j. F Y')
                )
            );
        }
    }
}

<?php

namespace Qafoo\TimePlannerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IcsImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('timeplanner:public-holiday:import')
            ->setDescription('Import ICS feed with public holidays')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'URL of ICS calendar feed; Example feed source: http://www.schulferien.eu/downloads/feiertage-im-ical-format/'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $publicHolidayService = $this->getContainer()->get('qafoo.time_planner.domain.public_holiday_ics_importer');
        $holidays = $publicHolidayService->import($input->getArgument('url'));

        $output->writeln("Imported:");
        foreach ($holidays as $holiday) {
            $output->writeln(
                sprintf(
                    " - %s: %s",
                    $holiday->date->format('d. F Y'),
                    $holiday->name
                )
            );
        }
    }
}

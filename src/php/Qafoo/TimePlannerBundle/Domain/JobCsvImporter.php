<?php

namespace Qafoo\TimePlannerBundle\Domain;

class JobCsvImporter
{
    /**
     * Service
     *
     * @var JobService
     */
    private $service;

    public function __construct(JobService $service)
    {
        $this->service = $service;
    }

    /**
     * Import from given URL
     *
     * @param string $month
     * @param string $url
     * @return Job[]
     */
    public function import($month, $url)
    {
        $month = new \DateTimeImmutable($month . '-01');
        $tableData = $this->getRows($url);

        $tableData = array_slice($tableData, 1);
        $jobs = array();
        foreach ($tableData as $row) {
            if (!$row[0] && !$row[1]) {
                // Indicates empty row
                continue;
            }

            if (in_array($row[1], array('Summe', 'Verfügbar', 'Differenz'))) {
                continue;
            }

            $job = new Job();
            $job->month = $month;
            $job->customer = $row[0] ?: 'Qafoo Internal';
            $job->project = $row[1];
            $job->personDays->minimum = $row[3];
            $job->personDays->maximum = $row[4];
            $job->expectedRevenue = (int) preg_replace('(\D+)', '', $row[2]) / 100;
            $job->invoiceId = $row[12];
            $job->comment = $row[13];

            foreach (array('manuel', null, 'kore', 'toby', 'benjamin') as $nr => $user) {
                if (!($row[5 + $nr])) {
                    continue;
                }

                $job->assignees[$user] = new Job\Assignment($user, (int) $row[5 + $nr]);
            }

            $jobs[] = $this->service->store($job);
        }

        return $jobs;
    }

    /**
     * Get rows
     *
     * @param string $url
     * @return array[]
     */
    protected function getRows($url)
    {
        $data = array();
        $handle = fopen($url, 'r');
        while ($row = fgetcsv($handle)) {
            $data[] = $row;
        }
        return $data;
    }
}

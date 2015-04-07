<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Qafoo\UserBundle\Domain\UserService;

class VacationCsvImporter
{
    /**
     * Vacation service
     *
     * @var VacationService
     */
    private $vacationService;

    /**
     * User service
     *
     * @var UserService
     */
    private $userService;

    public function __construct(VacationService $vacationService, UserService $userService)
    {
        $this->vacationService = $vacationService;
        $this->userService = $userService;
    }

    /**
     * Import from given URL
     *
     * @param string $month
     * @param string $url
     * @return Vacation[]
     */
    public function import($url, array $userMapping)
    {
        $tableData = $this->getRows($url);

        $tableData = array_slice($tableData, 1);
        $vacations = array();
        foreach ($tableData as $row) {
            if (!$row[0]) {
                // Indicates empty row
                continue;
            }

            if (!isset($userMapping[$row[0]])) {
                continue;
            }

            $vacation = new Vacation();
            $vacation->user = $this->userService->getUserByLogin($userMapping[$row[0]]);
            $vacation->start = new \DateTimeImmutable($row[1]);
            $vacation->end = new \DateTimeImmutable($row[2]);
            $vacations[] = $this->vacationService->store($vacation);
        }

        return $vacations;
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

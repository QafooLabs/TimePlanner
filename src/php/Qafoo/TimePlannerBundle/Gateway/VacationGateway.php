<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Qafoo\TimePlannerBundle\Domain\Vacation;
use Qafoo\TimePlannerBundle\Domain\DaySet;
use Qafoo\TimePlannerBundle\Domain\Day;

abstract class VacationGateway
{
    /**
     * Find
     *
     * @param string $vacationId
     * @return PublicHoliday
     */
    abstract public function get($vacationId);

    /**
     * Store
     *
     * @param Vacation $vacation
     * @return Vacation
     */
    abstract public function store(Vacation $vacation);

    /**
     * Remove
     *
     * @param Vacation $vacation
     * @return void
     */
    abstract public function remove(Vacation $vacation);

    /**
     * Get vacation days
     *
     * Unfiltered, without any processing of weekends or public holidays.
     *
     * @param string $user
     * @param int $year
     * @param int $year
     * @return DaySet
     */
    abstract public function getVacationDays($user, $year, $month = null);

    /**
     * Get vacation days per user
     *
     * @param int $year
     * @param int $month
     * @return DaySet[]
     */
    abstract public function getVacationDaysPerUser($year, $month = null);

    /**
     * Get vacations
     *
     * @param int $year
     * @return Vacation[]
     */
    abstract public function getVacations($year = null);

    /**
     * Get years
     *
     * @return int[]
     */
    abstract public function getYears();
}

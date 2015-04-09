<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Qafoo\UserBundle\Domain\User;
use Qafoo\TimePlannerBundle\Gateway\VacationGateway;

class VacationService
{
    /**
     * Vacation gateway
     *
     * @var VacationGateway
     */
    private $vacationGateway;

    /**
     * Public holiday service
     *
     * @var PublicHolidayService
     */
    private $publicHolidayService;

    public function __construct(VacationGateway $vacationGateway, PublicHolidayService $publicHolidayService)
    {
        $this->vacationGateway = $vacationGateway;
        $this->publicHolidayService = $publicHolidayService;
    }

    /**
     * Get remaining vacation days
     *
     * @param User $user
     * @param int $year
     * @return Date[]
     */
    public function getVacationDays(User $user, $year)
    {
        return $this->getVacationDaysPerUser($year)[$user->login];
    }

    /**
     * Get years
     *
     * @return int[]
     */
    public function getYears()
    {
        return $this->vacationGateway->getYears();
    }

    /**
     * Get vacations
     *
     * @param int $year
     * @return Vacation[]
     */
    public function getVacations($year)
    {
        return $this->vacationGateway->getVacations($year);
    }

    /**
     * Get vacation days per user
     *
     * @param int $year
     * @param int $month
     * @return DaySet[]
     */
    public function getVacationDaysPerUser($year, $month = null)
    {
        $userVacations = $this->vacationGateway->getVacationDaysPerUser($year, $month);

        foreach ($userVacations as $user => $vacations) {
            $vacations = $vacations->diff(
                DaySet::createFromRange(
                    new Day("1.1.$year"),
                    new Day("31.12.$year")
                )->filter(
                    function (Day $day) {
                        return $day->isWeekend();
                    }
                )
            );

            $userVacations[$user] = $vacations->diff($this->publicHolidayService->getHolidayDays($year, $month));
        }

        return $userVacations;
    }

    /**
     * Store
     *
     * @param Vacation $vacation
     * @return Vacation
     */
    public function store(Vacation $vacation)
    {
        return $this->vacationGateway->store($vacation);
    }

    /**
     * Remove
     *
     * @param Vacation $vacation
     * @return void
     */
    public function remove(Vacation $vacation)
    {
        return $this->vacationGateway->remove($vacation);
    }
}

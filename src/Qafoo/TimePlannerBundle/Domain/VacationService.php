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
        $weekendDays = $this->getWeekendDays($year);
        $publicHolidays = array_map(
            function (PublicHoliday $publicHoliday) {
                return Date::fromDateTime($publicHoliday->date)->format("Y-m-d");
            },
            $this->publicHolidayService->getHolidays($year)
        );

        $vacationDays = array_map(
            function (\DateTimeInterface $dateTime) {
                return Date::fromDateTime($dateTime)->format("Y-m-d");
            },
            $this->vacationGateway->getVacationDays($user->login, $year)
        );

        $vacationDays = array_diff($vacationDays, $weekendDays);
        $vacationDays = array_diff($vacationDays, $publicHolidays);

        return array_map(
            function ($dayString) {
                return new Date($dayString);
            },
            $vacationDays
        );
    }

    /**
     * Get weekend days
     *
     * @param string $year
     * @return \DateTimeImmutable[]
     */
    protected function getWeekendDays($year)
    {
        $weekendDays = array();
        $startDate = new Date("1.1.$year 12:00");
        $endDate = new Date("1.1." . ($year + 1));
        do {
            if ($startDate->isWeekend()) {
                // Weekend
                $weekendDays[] = $startDate->format("Y-m-d");
            }

            $startDate = $startDate->modify("+1 day");
        } while ($startDate < $endDate);

        return $weekendDays;
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

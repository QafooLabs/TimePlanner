<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Qafoo\UserBundle\Domain\User;
use Qafoo\TimePlannerBundle\Gateway\Vacation as VacationGateway;

class VacationService
{
    /**
     * Vacation gateway
     *
     * @var VacationGateway
     */
    private $vacationGateway;

    public function __construct(VacationGateway $vacationGateway)
    {
        $this->vacationGateway = $vacationGateway;

    }

    /**
     * Get remaining vacation days
     *
     * @param User $user
     * @param int $year
     * @return int
     */
    public function getRemainingVacationDays(User $user, $year)
    {
        return 30 - count($this->vacationGateway->getVacationDays($user->login, $year));
    }

    /**
     * Get next vacations
     *
     * @param int $count
     * @return Vacation[]
     */
    public function getNextVacations($count = 10)
    {
        return $this->vacationGateway->getNextVacations($count);
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
}

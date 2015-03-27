<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Qafoo\UserBundle\Domain\User;

class VacationService
{
    /**
     * Get remaining vacation days
     *
     * @param User $user
     * @return int
     */
    public function getRemainingVacationDays(User $user)
    {
        return 12;
    }

    /**
     * Get next vacations
     *
     * @param int $count
     * @return Vacation[]
     */
    public function getNextVacations($count = 10)
    {
        return array();
    }
}

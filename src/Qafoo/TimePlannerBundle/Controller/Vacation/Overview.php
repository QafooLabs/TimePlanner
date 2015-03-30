<?php

namespace Qafoo\TimePlannerBundle\Controller\Vacation;

use Kore\DataObject\DataObject;

class Overview extends DataObject
{
    /**
     * User
     *
     * @var User
     */
    public $user;

    /**
     * Remaining vacation
     *
     * @var int
     */
    public $remainingVacation;

    /**
     * Vacations
     *
     * @var Vacation[]
     */
    public $vacations;
}

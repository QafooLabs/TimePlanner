<?php

namespace Qafoo\TimePlannerBundle\Controller\View;

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

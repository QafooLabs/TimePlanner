<?php

namespace Qafoo\TimePlannerBundle\Controller\Vacation;

use Kore\DataObject\DataObject;

class AvailableVacation extends DataObject
{
    /**
     * User
     *
     * @var User
     */
    public $user;

    /**
     * Available vacation days
     *
     * @var int
     */
    public $available;

    /**
     * Booked vacation days
     *
     * @var DaySet
     */
    public $booked;
}

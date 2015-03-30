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
     * Years
     *
     * @var int[]
     */
    public $years = array();

    /**
     * Year
     *
     * @var int
     */
    public $year;

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

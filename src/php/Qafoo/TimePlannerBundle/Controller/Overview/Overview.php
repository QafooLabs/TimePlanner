<?php

namespace Qafoo\TimePlannerBundle\Controller\Overview;

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
     * Remaining vacations days
     *
     * @var int
     */
    public $remainingVacation;

    /**
     * Vacations
     *
     * @var Vacation[]
     */
    public $vacations = array();

    /**
     * Jobs
     *
     * @var Job[]
     */
    public $jobs = array();
}

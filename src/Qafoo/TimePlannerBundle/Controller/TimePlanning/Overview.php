<?php

namespace Qafoo\TimePlannerBundle\Controller\TimePlanning;

use Kore\DataObject\DataObject;

class Overview extends DataObject
{
    /**
     * Currently displayed year
     *
     * @var int
     */
    public $year;

    /**
     * Currently displayed month
     *
     * @var int
     */
    public $month;

    /**
     * User
     *
     * @var User
     */
    public $user;

    /**
     * Users
     *
     * @var User
     */
    public $users = array();

    /**
     * Available work days
     *
     * @var int
     */
    public $availableWorkDays;

    /**
     * Vacation days per user
     *
     * @var DaySet[]
     */
    public $vacationDays = array();

    /**
     * Jobs
     *
     * @var Job[]
     */
    public $jobs = array();

    /**
     * Summed up jobs
     *
     * @var Job
     */
    public $sum;

    /**
     * Job ID to highlight
     *
     * @var string
     */
    public $highlight;
}

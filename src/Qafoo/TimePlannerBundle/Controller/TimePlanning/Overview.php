<?php

namespace Qafoo\TimePlannerBundle\Controller\TimePlanning;

use Kore\DataObject\DataObject;

class Overview extends DataObject
{
    /**
     * Jobs
     *
     * @var Job[]
     */
    public $jobs = array();

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
}

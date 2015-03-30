<?php

namespace Qafoo\TimePlannerBundle\Controller\PublicHoliday;

use Kore\DataObject\DataObject;

class Overview extends DataObject
{
    /**
     * Public holidays
     *
     * @var PublicHoliday[]
     */
    public $holidays = array();

    /**
     * Years with defined public holidays
     *
     * @var int[]
     */
    public $years = array();
}

<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Kore\DataObject\DataObject;

class PublicHoliday extends DataObject
{
    /**
     * Public holiday ID
     *
     * @var string
     */
    public $publicHolidayId;

    /**
     * Name
     *
     * @var string
     */
    public $name;

    /**
     * Date
     *
     * @var \DateTime
     */
    public $date;
}

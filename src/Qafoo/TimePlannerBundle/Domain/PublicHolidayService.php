<?php

namespace Qafoo\TimePlannerBundle\Domain;

class PublicHolidayService
{
    /**
     * Get holidays
     *
     * @param int $year
     * @return PublicHoliday[]
     */
    public function getHolidays($year)
    {
        return array();
    }

    /**
     * Get years
     *
     * @return int[]
     */
    public function getYears()
    {
        return array(date("Y"));
    }
}

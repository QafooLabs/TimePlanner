<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Qafoo\TimePlannerBundle\Gateway\PublicHolidayGateway;

class PublicHolidayService
{
    /**
     * PublicHoliday gateway;
     *;
     * @var PublicHolidayGateway;
     */
    private $publicHolidayGateway;

    public function __construct(PublicHolidayGateway $publicHolidayGateway)
    {
        $this->publicHolidayGateway = $publicHolidayGateway;
    }

    /**
     * Get holidays
     *
     * @param int $year
     * @return PublicHoliday[]
     */
    public function getHolidays($year)
    {
        return $this->publicHolidayGateway->getHolidays($year);
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

    /**
     * Store
     *
     * @param PublicHoliday $publicHoliday
     * @return PublicHoliday
     */
    public function store(PublicHoliday $publicHoliday)
    {
        return $this->publicHolidayGateway->store($publicHoliday);
    }

    /**
     * Remove
     *
     * @param PublicHoliday $publicHoliday
     * @return void
     */
    public function remove(PublicHoliday $publicHoliday)
    {
        return $this->publicHolidayGateway->remove($publicHoliday);
    }
}

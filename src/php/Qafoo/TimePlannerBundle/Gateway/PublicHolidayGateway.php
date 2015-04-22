<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Doctrine\ODM\CouchDB\DocumentRepository;
use Doctrine\CouchDB\CouchDBClient;

use Qafoo\TimePlannerBundle\Domain\PublicHoliday;

abstract class PublicHolidayGateway
{
    /**
     * Find
     *
     * @param string $publicHolidayId
     * @return PublicHoliday
     */
    abstract public function get($publicHolidayId);

    /**
     * Get holidays
     *
     * @param int $year
     * @return PublicHoliday[]
     */
    abstract public function getHolidays($year);

    /**
     * Get years
     *
     * @return int[]
     */
    abstract public function getYears();

    /**
     * Store
     *
     * @param PublicHoliday $publicHoliday
     * @return PublicHoliday
     */
    abstract public function store(PublicHoliday $publicHoliday);

    /**
     * Remove
     *
     * @param PublicHoliday $publicHoliday
     * @return void
     */
    abstract public function remove(PublicHoliday $publicHoliday);
}

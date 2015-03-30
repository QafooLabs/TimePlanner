<?php

namespace Qafoo\TimePlannerBundle\Domain;

class PublicHolidayIcsImporter
{
    /**
     * Service
     *
     * @var PublicHolidayService
     */
    private $service;

    public function __construct(PublicHolidayService $service)
    {
        $this->service = $service;

    }

    /**
     * Import from given URL
     *
     * @param string $url
     * @return PublicHoliday[]
     */
    public function import($url)
    {
        return array();
    }
}

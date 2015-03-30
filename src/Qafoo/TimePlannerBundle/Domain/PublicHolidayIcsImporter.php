<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Sabre\VObject\Reader;

class PublicHolidayIcsImporter
{
    /**
     * Service
     *
     * @var PublicHolidayService
     */
    private $service;

    /**
     * ICS reader
     *
     * @var \Sabre\VObject\Reader
     */
    private $reader;

    public function __construct(PublicHolidayService $service, Reader $reader)
    {
        $this->service = $service;
        $this->reader = $reader;
    }

    /**
     * Import from given URL
     *
     * @param string $url
     * @return PublicHoliday[]
     */
    public function import($url)
    {
        $calendar = $this->reader->read(file_get_contents($url));

        $holidays = array();
        foreach ($calendar->VEVENT as $event) {
            $holiday = new PublicHoliday(
                array(
                    'name' => (string) $event->SUMMARY,
                    'date' => $event->DTSTART->getDateTime(),
                )
            );

            $holidays[] = $this->service->store($holiday);
        }

        return $holidays;
    }
}

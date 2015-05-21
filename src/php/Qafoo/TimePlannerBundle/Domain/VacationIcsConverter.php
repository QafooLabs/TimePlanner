<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Sabre\VObject\Component\VCalendar;

class VacationIcsConverter
{
    /**
     * Calendar handler
     *
     * @var VCalendar
     */
    private $calendar;

    public function __construct(VCalendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * Get ics calendar
     *
     * @index Visitor
     *
     * @param Vacation[] $vacations
     * @return string
     */
    public function convert(array $vacations)
    {
        foreach ($vacations as $vacation) {
            $vacation->user->getUsername();

            $event = $this->calendar->add('VEVENT');
            $event->add(
                'SUMMARY',
                sprintf(
                    "Vacation: %s %s",
                    ((string) $vacation->user->name) ?: $vacation->user->login,
                    ($vacation->comment ? ' (' . $vacation->comment . ')' : '')
                )
            );

            $start = $event->add('DTSTART', $vacation->start->modify('today'));
            $start['VALUE'] = 'DATE';

            $end = $event->add('DTEND', $vacation->end->modify('tomorrow'));
            $end['VALUE'] = 'DATE';
        }

        return $this->calendar->serialize();
    }
}

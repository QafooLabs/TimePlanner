<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Sabre\VObject\Component;

class VacationIcsVisitor
{
    /**
     * Get ics calendar
     *
     * @param Vacation[] $vacations
     * @return string
     */
    public function getIcsCalendar(array $vacations)
    {
        $calendar = new Component\VCalendar();
        foreach ($vacations as $vacation) {
            $vacation->user->getUsername();

            $event = $calendar->add('VEVENT');
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

            $end = $event->add('DTEND', $vacation->start->modify('tomorrow'));
            $end['VALUE'] = 'DATE';
        }

        return $calendar->serialize();
    }
}

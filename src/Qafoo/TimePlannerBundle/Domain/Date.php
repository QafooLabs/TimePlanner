<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Kore\DataObject\DataObject;

class Date extends \DateTimeImmutable
{
    /**
     * From date time
     *
     * @param \DateTimeInterface $dateTime
     * @return self
     */
    public static function fromDateTime(\DateTimeInterface $dateTime)
    {
        return new static($dateTime->format('r'));
    }

    /**
     * Is weekend
     *
     * @return bool
     */
    public function isWeekend()
    {
        return ($this->format('N') >= 6);
    }
}

<?php

namespace Qafoo\TimePlannerBundle\Domain;

class Day extends \DateTimeImmutable
{
    public function __construct($time = "now")
    {
        parent::__construct($time, new \DateTimeZone("UTC"));
    }

    /**
     * From date time
     *
     * @param \DateTimeInterface $dateTime
     * @return self
     */
    public static function fromDateTime(\DateTimeInterface $dateTime)
    {
        return new static($dateTime->format('Y-m-d'));
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

    /**
     * Compare
     *
     * @param Day $day
     * @return int
     */
    public function compare(Day $day)
    {
        if ((string) $this == (string) $day) {
            return 0;
        }
        return ($this > $day) ? 1 : -1;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format("Y-m-d");
    }
}

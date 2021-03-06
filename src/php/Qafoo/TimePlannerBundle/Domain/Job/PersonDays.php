<?php

namespace Qafoo\TimePlannerBundle\Domain\Job;

use Kore\DataObject\DataObject;

class PersonDays extends DataObject
{
    /**
     * Minimum
     *
     * @var int
     */
    public $minimum;

    /**
     * Maximum
     *
     * @var int
     */
    public $maximum;

    /**
     * __construct
     *
     * @index Validation
     *
     * @param int $minimum
     * @param int $maximum
     * @return void
     */
    public function __construct($minimum, $maximum = null)
    {
        $this->minimum = $minimum;
        $this->maximum = $maximum ?: $minimum;

        if ($this->maximum < $this->minimum) {
            throw new \OutOfBoundsException(
                "The maximum of {$this->maximum} should not be smaller then the minimum of {$this->minimum}."
            );
        }
    }
}

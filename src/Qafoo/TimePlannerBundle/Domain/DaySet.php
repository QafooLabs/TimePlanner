<?php

namespace Qafoo\TimePlannerBundle\Domain;

class DaySet extends \ArrayObject
{
    /**
     * __construct
     *
     * @param mixed $input
     * @param $flags
     * @param $iterator_class
     * @return void
     */
    public function __construct($input = array(), $flags = 0, $iterator_class = "ArrayIterator")
    {
        parent::__construct(array(), $flags, $iterator_class);

        foreach ($input as $value) {
            $this->append($value);
        }
    }

    /**
     * Diff
     *
     * Returns a new day set with all days contained in this day set minus the
     * days contained in the given day set.
     *
     * @param DaySet $set
     * @return DaySet
     */
    public function diff(DaySet $set)
    {
        $diff = new static();
        $setIndex = array_map(
            function (Day $day) {
                return (string) $day;
            },
            $set->getArrayCopy()
        );

        foreach ($this as $day) {
            if (!in_array((string) $day, $setIndex)) {
                $diff[] = $day;
            }
        }
        return $diff;
    }

    /**
     * Append
     *
     * @param mixed $value
     * @return void
     */
    public function append($value)
    {
        if ($value instanceof \DateTimeInterface) {
            $value = Day::fromDateTime($value);
        }

        if (!$value instanceof Day) {
            $value = new Day($value);
        }

        return parent::append($value);
    }
}

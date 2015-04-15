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
     * Create from range
     *
     * @param Day $start
     * @param Day $end
     * @return DaySet
     */
    public static function createFromRange(Day $start, Day $end)
    {
        $result = new static();
        do {
            $result->append($start);
            $start = $start->modify("+1 day");
        } while ($start <= $end);

        return $result;
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
     * Filter
     *
     * Returns a new day set filtered by the callback function
     *
     * The callback function is called with the Day to filter and should return
     * true or false. On false the Day will be removed from the set.
     *
     * @param \Closure $callback
     * @return DaySet
     */
    public function filter(\Closure $callback)
    {
        $result = new static();
        foreach ($this as $day) {
            if ($callback($day)) {
                $result[] = $day;
            }
        }

        return $result;
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

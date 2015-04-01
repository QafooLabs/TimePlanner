<?php

namespace Qafoo\TimePlannerBundle\Domain;

/**
 * @covers Qafoo\TimePlannerBundle\Domain\Day
 */
class DayTest extends \PHPUnit_Framework_TestCase
{
    public function getWeekDays()
    {
        return array(
            array(new Day("1.12.2014"), false),
            array(new Day("2.12.2014"), false),
            array(new Day("3.12.2014"), false),
            array(new Day("4.12.2014"), false),
            array(new Day("5.12.2014"), false),
            array(new Day("6.12.2014"), true),
            array(new Day("7.12.2014"), true),
        );
    }

    /**
     * @dataProvider getWeekDays
     */
    public function testIsWeekend(Day $day, $isWeekend)
    {
        $this->assertSame($isWeekend, $day->isWeekend());
    }

    public function getComparisonDays()
    {
        return array(
            array(new Day('1.1.2015 1:00'), new Day('1.1.2015 1:00'), 0),
            array(new Day('1.1.2015 1:00'), new Day('1.1.2015 15:00'), 0),
            array(new Day('1.1.2015 15:00'), new Day('1.1.2015 1:00'), 0),
            array(new Day('1.1.2015 23:00'), new Day('2.1.2015 1:00'), -1),
            array(new Day('1.1.2015 23:00'), new Day('31.12.2014 1:00'), 1),
        );
    }

    /**
     * @dataProvider getComparisonDays
     */
    public function testCompare(Day $a, Day $b, $result)
    {
        $this->assertSame($result, $a->compare($b));
    }

    public function getDates()
    {
        return array(
            array(new \DateTime('1.1.2015 0:00 UTC'), '2015-01-01'),
            array(new \DateTime('1.1.2015 0:00 Europe/Berlin'), '2015-01-01'),
            array(new \DateTime('1.1.2015 0:00 GMT+6'), '2015-01-01'),
            array(new \DateTime('1.1.2015 0:00 GMT-6'), '2015-01-01'),
            array(new \DateTime('1.1.2015 23:00 UTC'), '2015-01-01'),
            array(new \DateTime('1.1.2015 23:00 Europe/Berlin'), '2015-01-01'),
            array(new \DateTime('1.1.2015 23:00 GMT+6'), '2015-01-01'),
            array(new \DateTime('1.1.2015 23:00 GMT-6'), '2015-01-01'),
        );
    }

    /**
     * @dataProvider getDates
     */
    public function testCreateFromDateTime(\DateTimeInterface $date, $day)
    {
        $this->assertEquals(
            $day,
            (string) Day::fromDateTime($date)
        );
    }
}

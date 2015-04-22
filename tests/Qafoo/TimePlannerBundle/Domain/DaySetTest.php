<?php

namespace Qafoo\TimePlannerBundle\Domain;

/**
 * @covers Qafoo\TimePlannerBundle\Domain\DaySet
 */
class DaySetTest extends \PHPUnit_Framework_TestCase
{
    public function getConstructorInputs()
    {
        return array(
            array(array(new Day("1.1.2015"), new Day("3.1.2015"))),
            array(array(new \DateTimeImmutable("1.1.2015"), new \DateTime("3.1.2015"))),
            array(array("1.1.2015", "3.1.2015")),
        );
    }

    /**
     * @dataProvider getConstructorInputs
     */
    public function testConstructorInputs(array $input)
    {
        $this->assertEquals(
            new DaySet(array(
                new Day("1.1.2015"),
                new Day("3.1.2015"),
            )),
            new DaySet($input)
        );
    }

    public function testDiffCreatesCopy()
    {
        $startSet = new DaySet(array('1.1.2015'));

        $this->assertNotSame(
            $startSet,
            $startSet->diff(new DaySet())
        );
    }

    public function testDiffNothing()
    {
        $startSet = new DaySet(array('1.1.2015', '2.1.2015', '3.1.2015'));

        $this->assertEquals(
            $startSet,
            $startSet->diff(new DaySet(array('5.1.2015')))
        );
    }

    public function testDiffDay()
    {
        $startSet = new DaySet(array('1.1.2015', '2.1.2015', '3.1.2015'));

        $this->assertEquals(
            new DaySet(array('1.1.2015', '3.1.2015')),
            $startSet->diff(new DaySet(array('2.1.2015')))
        );
    }

    public function testCreateFromRange()
    {
        $this->assertEquals(
            new DaySet(array('1.1.2015', '2.1.2015', '3.1.2015')),
            DaySet::createFromRange(new Day('1.1.2015'), new Day('3.1.2015'))
        );
    }

    public function testFilter()
    {
        $this->assertEquals(
            new DaySet(array('1.1.2015', '3.1.2015')),
            DaySet::createFromRange(new Day('1.1.2015'), new Day('3.1.2015'))->filter(
                function (Day $day) {
                    return $day->format('d') % 2;
                }
            )
        );
    }

    public function testContains()
    {
        $this->assertTrue(
            DaySet::createFromRange(new Day('1.1.2015'), new Day('5.1.2015'))->contains(
                new Day('2.1.2015')
            )
        );
    }

    public function testNotContains()
    {
        $this->assertFalse(
            DaySet::createFromRange(new Day('1.1.2015'), new Day('5.1.2015'))->contains(
                new Day('7.1.2015')
            )
        );
    }

    public function testMerge()
    {
        $this->assertEquals(
            DaySet::createFromRange(new Day('1.1.2015'), new Day('5.1.2015')),
            DaySet::createFromRange(new Day('1.1.2015'), new Day('3.1.2015'))->merge(
                DaySet::createFromRange(new Day('3.1.2015'), new Day('5.1.2015'))
            )
        );
    }
}

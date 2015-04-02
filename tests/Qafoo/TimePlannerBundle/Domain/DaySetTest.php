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
}
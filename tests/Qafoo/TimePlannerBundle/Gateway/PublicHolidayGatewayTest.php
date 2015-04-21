<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Qafoo\TimePlannerBundle\Domain\PublicHoliday;
use Qafoo\TimePlannerBundle\Domain\Day;

use Doctrine\Common\Collections\ArrayCollection;
use Qafoo\IntegrationTest;

/**
 * @covers Qafoo\TimePlannerBundle\Gateway\PublicHolidayGateway
 */
class PublicHolidayGatewayTest extends IntegrationTest
{
    public function testStorePublicHoliday()
    {
        $publicHolidayGateway = $this->getContainer()->get('qafoo.time_planner.gateway.public_holiday');

        $holiday = new PublicHoliday();
        $holiday->name = 'Test';
        $holiday->date = new \DateTime('2014-01-01');
        $publicHolidayGateway->store($holiday);

        $this->assertNotNull($holiday->publicHolidayId);
        return $holiday;
    }

    /**
     * @depends testStorePublicHoliday
     */
    public function testGetPublicHoliday($holiday)
    {
        $publicHolidayGateway = $this->getContainer()->get('qafoo.time_planner.gateway.public_holiday');
        $loaded = $publicHolidayGateway->get($holiday->publicHolidayId);

        $this->assertEquals($holiday, $loaded);
    }

    /**
     * @depends testGetPublicHoliday
     * @expectedException OutOfBoundsException
     */
    public function testFailGettingPublicHoliday()
    {
        $publicHolidayGateway = $this->getContainer()->get('qafoo.time_planner.gateway.public_holiday');
        $publicHolidayGateway->get('loaded');
    }

    /**
     * @depends testStorePublicHoliday
     */
    public function testGetHolidaysPerYear($holiday)
    {
        $publicHolidayGateway = $this->getContainer()->get('qafoo.time_planner.gateway.public_holiday');

        $this->assertEquals(
            array($holiday),
            $publicHolidayGateway->getHolidays(2014)
        );
    }

    /**
     * @depends testStorePublicHoliday
     */
    public function testGetYears($holiday)
    {
        $publicHolidayGateway = $this->getContainer()->get('qafoo.time_planner.gateway.public_holiday');

        $this->assertEquals(
            array(2014),
            $publicHolidayGateway->getYears()
        );
    }

    /**
     * @depends testStorePublicHoliday
     */
    public function testRemoveHoliday($holiday)
    {
        $publicHolidayGateway = $this->getContainer()->get('qafoo.time_planner.gateway.public_holiday');
        $holiday = $publicHolidayGateway->get($holiday->publicHolidayId);

        $publicHolidayGateway->remove($holiday);

        $this->assertEquals(
            array(),
            $publicHolidayGateway->getHolidays(2014)
        );
    }
}

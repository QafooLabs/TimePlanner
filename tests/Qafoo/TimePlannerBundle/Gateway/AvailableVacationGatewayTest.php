<?php

namespace Qafoo\TimePlannerBundle\Gateway;

/**
 * @covers Qafoo\TimePlannerBundle\Gateway\AvailableVacationGateway
 */
class AvailableVacationGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDefaultVacation()
    {
        $gateway = new AvailableVacationGateway();

        $this->assertEquals(30, $gateway->getAvailableVacationDays('test', 2015));
    }

    public function testGetCustomDefaultVacation()
    {
        $gateway = new AvailableVacationGateway(25);

        $this->assertEquals(25, $gateway->getAvailableVacationDays('test', 2015));
    }

    public function testGetCustomVacation()
    {
        $gateway = new AvailableVacationGateway(
            25,
            array(
                2015 => array(
                    'test' => 35
                )
            )
        );

        $this->assertEquals(35, $gateway->getAvailableVacationDays('test', 2015));
    }
}

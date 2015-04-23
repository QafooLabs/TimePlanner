<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Qafoo\TimePlannerBundle\Domain\Vacation;
use Qafoo\TimePlannerBundle\Domain\MetaData;
use Qafoo\TimePlannerBundle\Domain\DaySet;
use Qafoo\TimePlannerBundle\Domain\Day;
use Qafoo\UserBundle\Domain\FOSUser;

use Doctrine\Common\Collections\ArrayCollection;
use Qafoo\IntegrationTest;

/**
 * @covers Qafoo\TimePlannerBundle\Gateway\VacationGateway
 */
class VacationGatewayTest extends IntegrationTest
{
    public function testStoreVacation()
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');
        $user = $this->getUser();

        $vacation = new Vacation();
        $vacation->start = new \DateTime("2014-12-25");
        $vacation->end = new \DateTime("2015-01-05");
        $vacation->user = $user;
        $vacation->metaData = new MetaData($user->login);
        $vacationGateway->store($vacation);

        $this->assertNotNull($vacation->vacationId);
        return $vacation;
    }

    /**
     * @depends testStoreVacation
     */
    public function testGetVacation($vacation)
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');

        $this->assertVacationEquals(
            $vacation,
            $vacationGateway->get($vacation->vacationId)
        );
    }

    /**
     * @depends testGetVacation
     * @expectedException OutOfBoundsException
     */
    public function testFailGetInvalidVacation()
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');
        $vacationGateway->get('unknown');
    }

    /**
     * @depends testStoreVacation
     */
    public function testVacationDaysForExistingUser()
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');

        $this->assertEquals(
            DaySet::createFromRange(new Day('1.1.2015'), new Day('5.1.2015')),
            $vacationGateway->getVacationDays('kore', 2015, 01)
        );
    }

    /**
     * @depends testStoreVacation
     */
    public function testVacationDaysForExistingUserForYear()
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');

        $this->assertEquals(
            DaySet::createFromRange(new Day('25.12.2014'), new Day('31.12.2014')),
            $vacationGateway->getVacationDays('kore', 2014)
        );
    }

    /**
     * @depends testStoreVacation
     */
    public function testVacationDaysForNonExistingUser()
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');

        $this->assertEquals(
            new DaySet(),
            $vacationGateway->getVacationDays('unknown', 2015, 01)
        );
    }

    /**
     * @depends testStoreVacation
     */
    public function testGetYears($vacation)
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');

        $secondVacation = new Vacation();
        $secondVacation->start = new \DateTime("2015-03-01");
        $secondVacation->end = new \DateTime("2015-03-05");
        $secondVacation->user = $userGateway->loadUserByUsername('kore');
        $vacationGateway->store($secondVacation);

        $this->assertEquals(
            array(2014, 2015),
            $vacationGateway->getYears()
        );

        return array($vacation, $secondVacation);
    }

    /**
     * @depends testGetYears
     */
    public function testGetVacationsForEndYear(array $vacations)
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');

        $this->assertVacationEquals(
            $vacations,
            $vacationGateway->getVacations(2015)
        );
    }

    /**
     * @depends testGetYears
     */
    public function testGetVacationsForStartYear(array $vacations)
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');

        $this->assertVacationEquals(
            array_slice($vacations, 0, 1),
            $vacationGateway->getVacations(2014)
        );
    }

    /**
     * @depends testStoreVacation
     */
    public function testGetVacationsForUnknownYear($vacation)
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');

        $this->assertVacationEquals(
            array(),
            $vacationGateway->getVacations(2013)
        );
    }

    /**
     * @depends testStoreVacation
     */
    public function testRemoveVacation($vacation)
    {
        $vacationGateway = $this->getContainer()->get('qafoo.time_planner.gateway.vacation');
        $vacation = $vacationGateway->get($vacation->vacationId);

        $vacationGateway->remove($vacation);

        $this->assertVacationEquals(
            array(),
            $vacationGateway->getVacations(2014)
        );
    }

    /**
     * Assert vacation equals
     *
     * We use this custom assertion to ignore Doctrine proxy objects and
     * differntly constructed date time objects.
     *
     * @index CustomAssertion
     *
     * @param Vacation|Vacation[] $expectation
     * @param Vacation|Vacation[] $actual
     * @return void
     */
    protected function assertVacationEquals($expectation, $actual)
    {
        $this->assertEquals(
            is_array($expectation) ?
                array_map(array($this, 'getVacationValues'), $expectation) :
                $this->getVacationValues($expectation),
            is_array($actual) ?
                array_map(array($this, 'getVacationValues'), $actual) :
                $this->getVacationValues($actual),
            "Vacation properties do not match"
        );
    }

    /**
     * Get vacation values
     *
     * @param Vacation $vacation
     * @return array
     */
    protected function getVacationValues(Vacation $vacation)
    {
        $compareProperties = array('vacationId', 'start', 'end', 'comment');
        return array_intersect_key((array) $vacation, array_flip($compareProperties));
    }

    protected function getUser()
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');

        $user = new FOSUser();
        $user->login = 'kore';
        $userGateway->store($user);

        return $user;
    }
}

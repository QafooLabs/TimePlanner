<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Qafoo\TimePlannerBundle\Domain\Job;
use Qafoo\TimePlannerBundle\Domain\MetaData;
use Qafoo\UserBundle\Domain\FOSUser;

use Doctrine\Common\Collections\ArrayCollection;
use Qafoo\IntegrationTest;

/**
 * @covers Qafoo\TimePlannerBundle\Gateway\MetaDataGateway
 */
class MetaDataGatewayTest extends IntegrationTest
{
    public function testQueryMetaData()
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $metadataGateway = $this->getContainer()->get('qafoo.time_planner.gateway.metadata');

        $user = new FOSUser();
        $user->login = 'kore';
        $userGateway->store($user);

        $job = new Job();
        $job->month = new \DateTime('1982-04');
        $job->customer = 'Customer';
        $job->project = 'Project';
        $job->metaData = new MetaData($user);
        $jobGateway->store($job);

        $this->assertEquals(
            array($job),
            $metadataGateway->getLastEdits(Job::class)
        );
    }

    /**
     * @depends testQueryMetaData
     */
    public function testQueryNoDataWithWrongType()
    {
        $metadataGateway = $this->getContainer()->get('qafoo.time_planner.gateway.metadata');

        $this->assertEquals(
            array(),
            $metadataGateway->getLastEdits('unknown')
        );
    }
}

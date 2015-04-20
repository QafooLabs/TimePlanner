<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Qafoo\TimePlannerBundle\Domain\Job;

use Doctrine\Common\Collections\ArrayCollection;
use Qafoo\IntegrationTest;

/**
 * @covers Qafoo\TimePlannerBundle\Gateway\JobGateway
 */
class JobGatewayTest extends IntegrationTest
{
    public function setUp()
    {
        parent::setUp();

        // Used to remove internal reference and ensure the Job is reloaded
        $documentManager = $this->getContainer()->get('doctrine_couchdb.odm.document_manager');
        $documentManager->clear();
    }

    public function testStoreNewJob()
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');

        $job = new Job();
        $job->month = new \DateTime('1982-04');
        $job->customer = 'Customer';
        $job->project = 'Project';
        $job->assignees = new ArrayCollection();

        $job = $jobGateway->store($job);

        $this->assertNotNull($job->jobId);
        $this->assertNotNull($job->revision);

        return $job;
    }

    /**
     * @depends testStoreNewJob
     */
    public function testGetJob(Job $job)
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');

        $this->assertEquals(
            $job,
            $jobGateway->get($job->jobId)
        );
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testFailGetJob()
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');

        $jobGateway->get('unknown');
    }

    /**
     * @depends testStoreNewJob
     */
    public function testGetJobs(Job $job)
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');

        $this->assertEquals(
            array($job),
            $jobGateway->getJobs(1982, 4)
        );
    }

    /**
     * @depends testStoreNewJob
     */
    public function testGetNoJobs(Job $job)
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');

        $this->assertEquals(
            array(),
            $jobGateway->getJobs(1982, 1)
        );
    }

    /**
     * @depends testStoreNewJob
     */
    public function testGetCustomers(Job $job)
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');

        $this->assertEquals(
            array($job->customer),
            $jobGateway->getCustomers()
        );
    }

    /**
     * @depends testStoreNewJob
     */
    public function testGetProjects(Job $job)
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');

        $this->assertEquals(
            array($job->project),
            $jobGateway->getProjects()
        );
    }

    /**
     * @depends testStoreNewJob
     */
    public function testUpdateCorrectRevision(Job $job)
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');

        $job = $jobGateway->get($job->jobId);
        $job->customer = $customer = 'New Customer';
        $updated = $jobGateway->store($job);

        $this->assertEquals(array($customer), $jobGateway->getCustomers());
        return $updated;
    }

    /**
     * @depends testStoreNewJob
     * @expectedException LogicException
     */
    public function testFailUpdateWrongRevision(Job $job)
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');
        $job = clone $job;
        $job->revision = 'WrongVersion';
        $job->customer = $customer = 'New Customer';

        return $jobGateway->store($job);
    }

    /**
     * @depends testUpdateCorrectRevision
     * @expectedException LogicException
     */
    public function testFailRemoveJobWrongRevision(Job $job)
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');

        $job->jobId = $job->jobId;
        $job->revision = 'WrongVersion';

        $jobGateway->remove($job);
    }

    /**
     * @depends testUpdateCorrectRevision
     */
    public function testRemoveJob(Job $job)
    {
        $jobGateway = $this->getContainer()->get('qafoo.time_planner.gateway.job');

        $job = $jobGateway->get($job->jobId);
        $jobGateway->remove($job);

        $this->assertEquals(
            array(),
            $jobGateway->getJobs(1982, 4)
        );
    }
}

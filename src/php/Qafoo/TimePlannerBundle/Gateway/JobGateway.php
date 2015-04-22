<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Doctrine\ODM\CouchDB\DocumentRepository;
use Doctrine\CouchDB\CouchDBClient;

use Qafoo\TimePlannerBundle\Domain\Job;

abstract class JobGateway
{
    /**
     * Find
     *
     * @param string $jobId
     * @return PublicHoliday
     */
    abstract public function get($jobId);

    /**
     * Get jobs
     *
     * @param int $year
     * @param int $month
     * @return Job[]
     */
    abstract public function getJobs($year, $month);

    /**
     * Get customers
     *
     * @return string[]
     */
    abstract public function getCustomers();

    /**
     * Get projects
     *
     * @return string[]
     */
    abstract public function getProjects();

    /**
     * Store
     *
     * @param Job $job
     * @return Job
     */
    abstract public function store(Job $job);

    /**
     * Remove
     *
     * @param Job $job
     * @return void
     */
    abstract public function remove(Job $job);
}

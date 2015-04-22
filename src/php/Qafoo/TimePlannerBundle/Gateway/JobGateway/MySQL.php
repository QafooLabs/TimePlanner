<?php

namespace Qafoo\TimePlannerBundle\Gateway\JobGateway;

use Qafoo\TimePlannerBundle\Gateway\JobGateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Qafoo\TimePlannerBundle\Domain\Job;

class MySQL extends JobGateway
{
    /**
     * Entity repository
     *
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * Entity manager
     *
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityRepository $entityRepository, EntityManager $entityManager)
    {
        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Find
     *
     * @param string $jobId
     * @return PublicHoliday
     */
    public function get($jobId)
    {
        if (($job = $this->entityRepository->find($jobId)) === null) {
            throw new \OutOfBoundsException("Job with id $jobId not found.");
        }

        return $job;
    }

    /**
     * Get jobs
     *
     * @param int $year
     * @param int $month
     * @return Job[]
     */
    public function getJobs($year, $month)
    {
        $query = $this->entityManager->createQuery(
            "SELECT j
                FROM Qafoo\TimePlannerBundle\Domain\Job j
                WHERE j.month >= :start
                  AND j.month < :end"
        );

        $month = new \DateTimeImmutable("01.$month.$year");
        $query->setParameter('start', $month->modify('first day of this month'));
        $query->setParameter('end', $month->modify('first day of next month'));

        return $query->getResult();
    }

    /**
     * Get customers
     *
     * @return string[]
     */
    public function getCustomers()
    {
        $query = $this->entityManager->getConnection()->query(
            "SELECT DISTINCT(j_customer) FROM job"
        );
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Get projects
     *
     * @return string[]
     */
    public function getProjects()
    {
        $query = $this->entityManager->getConnection()->query(
            "SELECT DISTINCT(j_project) FROM job"
        );
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Check revision
     *
     * @param Job $job
     * @return void
     */
    protected function checkRevision(Job $job)
    {
        $query = $this->entityManager->getConnection()->prepare(
            "SELECT j_revision FROM job WHERE j_id = :jobId"
        );
        $query->bindValue('jobId', $job->jobId);
        $query->execute();

        if (($revision = $query->fetchColumn()) === false) {
            return;
        }

        if ($revision !== $job->revision) {
            throw new \LogicException(
                'Job had been updated by some else in the mean time.'
            );
        }
    }

    /**
     * Store
     *
     * @param Job $job
     * @return Job
     */
    public function store(Job $job)
    {
        $this->checkRevision($job);

        $job->revision = md5(microtime());
        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $job;
    }

    /**
     * Remove
     *
     * @param Job $job
     * @return void
     */
    public function remove(Job $job)
    {
        $this->checkRevision($job);

        $this->entityManager->remove($job);
        $this->entityManager->flush();
    }
}

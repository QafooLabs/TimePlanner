<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Doctrine\ODM\CouchDB\DocumentRepository;
use Doctrine\CouchDB\CouchDBClient;

use Qafoo\TimePlannerBundle\Domain\Job;

class JobGateway
{
    /**
     * Document repository
     *
     * @var DocumentRepository
     */
    private $documentRepository;

    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * Find
     *
     * @param string $jobId
     * @return PublicHoliday
     */
    public function get($jobId)
    {
        return $this->documentRepository->find($jobId);
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
        $query = $this->documentRepository->getDocumentManager()->createQuery('jobs', 'index');
        $result = $query
            ->setStartKey(array((int) $year, (int) $month))
            ->setEndKey(array((int) $year, (int) $month, CouchDBClient::COLLATION_END))
            ->setIncludeDocs(true)
            ->setReduce(false)
            ->onlyDocs(true)
            ->execute();

        return $result->toArray();
    }

    /**
     * Get customers
     *
     * @return string[]
     */
    public function getCustomers()
    {
        $query = $this->documentRepository->getDocumentManager()->createQuery('jobs', 'customers');
        $result = $query
            ->setIncludeDocs(false)
            ->setReduce(true)
            ->setGroup(true)
            ->execute();

        return array_map(
            function (array $row) {
                return $row['key'];
            },
            $result->toArray()
        );
    }

    /**
     * Get projects
     *
     * @return string[]
     */
    public function getProjects()
    {
        $query = $this->documentRepository->getDocumentManager()->createQuery('jobs', 'projects');
        $result = $query
            ->setIncludeDocs(false)
            ->setReduce(true)
            ->setGroup(true)
            ->execute();

        return array_map(
            function (array $row) {
                return $row['key'];
            },
            $result->toArray()
        );
    }

    /**
     * Store
     *
     * @param Job $job
     * @return Job
     */
    public function store(Job $job)
    {
        $documentManager = $this->documentRepository->getDocumentManager();
        $documentManager->persist($job);
        $documentManager->flush();

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
        $documentManager = $this->documentRepository->getDocumentManager();
        $documentManager->remove($job);
        $documentManager->flush();
    }
}

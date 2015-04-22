<?php

namespace Qafoo\TimePlannerBundle\Gateway\PublicHolidayGateway;

use Qafoo\TimePlannerBundle\Gateway\PublicHolidayGateway;

use Doctrine\ODM\CouchDB\DocumentRepository;
use Doctrine\CouchDB\CouchDBClient;

use Qafoo\TimePlannerBundle\Domain\PublicHoliday;

class CouchDB extends PublicHolidayGateway
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
     * @param string $publicHolidayId
     * @return PublicHoliday
     */
    public function get($publicHolidayId)
    {
        if (($holiday = $this->documentRepository->find($publicHolidayId)) === null) {
            throw new \OutOfBoundsException("Public holiday with id $publicHolidayId not found.");
        }

        return $holiday;
    }

    /**
     * Get holidays
     *
     * @param int $year
     * @return PublicHoliday[]
     */
    public function getHolidays($year)
    {
        $query = $this->documentRepository->getDocumentManager()->createQuery('public_holidays', 'index');
        $result = $query
            ->setStartKey(array((int) $year))
            ->setEndKey(array((int) $year, CouchDBClient::COLLATION_END))
            ->setIncludeDocs(true)
            ->setReduce(false)
            ->onlyDocs(true)
            ->execute();

        return $result->toArray();
    }

    /**
     * Get years
     *
     * @return int[]
     */
    public function getYears()
    {
        $query = $this->documentRepository->getDocumentManager()->createQuery('public_holidays', 'index');
        $result = $query
            ->setGroup(true)
            ->setGroupLevel(1)
            ->setReduce(true)
            ->execute();

        return array_map(
            function (array $row) {
                return $row['key'][0];
            },
            $result->toArray()
        );
    }

    /**
     * Store
     *
     * @param PublicHoliday $publicHoliday
     * @return PublicHoliday
     */
    public function store(PublicHoliday $publicHoliday)
    {
        $documentManager = $this->documentRepository->getDocumentManager();
        $documentManager->persist($publicHoliday);
        $documentManager->flush();

        return $publicHoliday;
    }

    /**
     * Remove
     *
     * @param PublicHoliday $publicHoliday
     * @return void
     */
    public function remove(PublicHoliday $publicHoliday)
    {
        $documentManager = $this->documentRepository->getDocumentManager();
        $documentManager->remove($publicHoliday);
        $documentManager->flush();
    }
}

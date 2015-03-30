<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Doctrine\ODM\CouchDB\DocumentRepository;
use Doctrine\CouchDB\CouchDBClient;

use Qafoo\TimePlannerBundle\Domain\Vacation;

class VacationGateway
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
     * @param string $vacationId
     * @return PublicHoliday
     */
    public function get($vacationId)
    {
        return $this->documentRepository->find($vacationId);
    }

    /**
     * Get vacation days
     *
     * Unfiltered, without any processing of weekends or public holidays.
     *
     * @param string $user
     * @param int $year
     * @param int $year
     * @return \DateTimeImmutable[]
     */
    public function getVacationDays($user, $year, $month = null)
    {
        $parameters = array($user, (int) $year);
        if ($month !== null) {
            $parameters[] = (int) $month;
        }

        $query = $this->documentRepository->getDocumentManager()->createQuery('vacation', 'days');
        $result = $query
            ->setStartKey($parameters)
            ->setEndKey(array_merge($parameters, array(CouchDBClient::COLLATION_END)))
            ->setIncludeDocs(false)
            ->setReduce(false)
            ->execute();

        $days = array();
        foreach ($result as $row) {
            $days[] = new \DateTimeImmutable(
                "{$row['key'][1]}-{$row['key'][2]}-{$row['key'][3]} 12:00",
                new \DateTimeZone("UTC")
            );
        }

        return $days;
    }

    /**
     * Get next vacations
     *
     * @param int $count
     * @return Vacation[]
     */
    public function getNextVacations($count = 10)
    {
        $query = $this->documentRepository->getDocumentManager()->createQuery('vacation', 'upcoming');
        $result = $query
            ->setStartKey(date('Y-m-d'))
            ->setReduce(false)
            ->setLimit($count)
            ->onlyDocs(true)
            ->execute();

        return $result->toArray();
    }

    /**
     * Store
     *
     * @param Vacation $vacation
     * @return Vacation
     */
    public function store(Vacation $vacation)
    {
        $documentManager = $this->documentRepository->getDocumentManager();
        $documentManager->persist($vacation);
        $documentManager->flush();

        return $vacation;
    }

    /**
     * Remove
     *
     * @param Vacation $vacation
     * @return void
     */
    public function remove(Vacation $vacation)
    {
        $documentManager = $this->documentRepository->getDocumentManager();
        $documentManager->remove($vacation);
        $documentManager->flush();
    }
}

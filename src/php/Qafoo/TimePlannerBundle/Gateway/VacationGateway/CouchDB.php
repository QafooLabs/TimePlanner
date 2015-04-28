<?php

namespace Qafoo\TimePlannerBundle\Gateway\VacationGateway;

use Qafoo\TimePlannerBundle\Gateway\VacationGateway;

use Doctrine\ODM\CouchDB\DocumentRepository;
use Doctrine\CouchDB\CouchDBClient;

use Qafoo\TimePlannerBundle\Domain\Vacation;
use Qafoo\TimePlannerBundle\Domain\DaySet;
use Qafoo\TimePlannerBundle\Domain\Day;

class CouchDB extends VacationGateway
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
        if (($vacation = $this->documentRepository->find($vacationId)) === null) {
            throw new \OutOfBoundsException("Vacation with id $vacationId could not be found.");
        }

        return $vacation;
    }

    /**
     * Get vacation days
     *
     * Unfiltered, without any processing of weekends or public holidays.
     *
     * @param string $user
     * @param int $year
     * @param int $year
     * @return DaySet
     */
    public function getVacationDays($user, $year, $month = null)
    {
        $days = $this->getVacationDaysPerUser($year, $month);
        return isset($days[$user]) ? $days[$user] : new DaySet();
    }

    /**
     * Get years
     *
     * @return int[]
     */
    public function getYears()
    {
        $query = $this->documentRepository->getDocumentManager()->createQuery('vacation', 'index');
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
     * Get vacation days per user
     *
     * @param int $year
     * @param int $month
     * @return DaySet[]
     */
    public function getVacationDaysPerUser($year, $month = null)
    {
        $parameters = array((int) $year);
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
            list ($year, $month, $day, $user) = $row['key'];
            if (!isset($days[$user])) {
                $days[$user] = new DaySet();
            }

            $days[$user][] = new Day("$year-$month-$day");
        }

        return $days;
    }

    /**
     * Get vacations
     *
     * @param int $year
     * @return Vacation[]
     */
    public function getVacations($year = null)
    {
        $parameters = array();
        if ($year !== null) {
            $parameters[] = (int) $year;
        }

        $query = $this->documentRepository->getDocumentManager()->createQuery('vacation', 'index');
        $result = $query
            ->setStartKey($parameters)
            ->setEndKey(array_merge($parameters, array(CouchDBClient::COLLATION_END)))
            ->setReduce(true)
            ->setGroup(true)
            ->execute();

        $vacationIds = array_map(
            function ($row) {
                return $row['key'][1];
            },
            $result->toArray()
        );

        $vacations = $this->documentRepository->findMany($vacationIds);
        usort(
            $vacations,
            function ($a, $b) {
                return ($a->start < $b->start) ? -1 : ($a->start > $b->start ? 1: 0);
            }
        );
        return $vacations;
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

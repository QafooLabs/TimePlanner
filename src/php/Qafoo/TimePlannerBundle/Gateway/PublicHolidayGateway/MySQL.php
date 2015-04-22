<?php

namespace Qafoo\TimePlannerBundle\Gateway\PublicHolidayGateway;

use Qafoo\TimePlannerBundle\Gateway\PublicHolidayGateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Qafoo\TimePlannerBundle\Domain\PublicHoliday;

class MySQL extends PublicHolidayGateway
{
    /**
     * Entity repository
     *
     * @var EntityRepository
     */
    protected $entityRepository;

    /**
     * Entity manager
     *
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityRepository $entityRepository, EntityManager $entityManager)
    {
        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Find
     *
     * @param string $publicHolidayId
     * @return PublicHoliday
     */
    public function get($publicHolidayId)
    {
        if (($holiday = $this->entityRepository->find($publicHolidayId)) === null) {
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
        $query = $this->entityManager->createQuery(
            "SELECT p
                FROM Qafoo\TimePlannerBundle\Domain\PublicHoliday p
                WHERE p.date >= :yearStart
                  AND p.date < :nextYearStart"
        );

        $year = new \DateTimeImmutable("01.01.$year");
        $query->setParameter('yearStart', $year->modify('first day of this year'));
        $query->setParameter('nextYearStart', $year->modify('first day of next year'));

        return $query->getResult();
    }

    /**
     * Get years
     *
     * @return int[]
     */
    public function getYears()
    {
        $query = $this->entityManager->getConnection()->query(
            "SELECT DISTINCT(YEAR(p_date)) FROM public_holiday"
        );
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Store
     *
     * @param PublicHoliday $publicHoliday
     * @return PublicHoliday
     */
    public function store(PublicHoliday $publicHoliday)
    {
        $this->entityManager->persist($publicHoliday);
        $this->entityManager->flush();

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
        $this->entityManager->remove($publicHoliday);
        $this->entityManager->flush();
    }
}

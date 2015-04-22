<?php

namespace Qafoo\TimePlannerBundle\Gateway\VacationGateway;

use Qafoo\TimePlannerBundle\Gateway\VacationGateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Qafoo\TimePlannerBundle\Domain\Vacation;
use Qafoo\TimePlannerBundle\Domain\DaySet;
use Qafoo\TimePlannerBundle\Domain\Day;

class MySQL extends VacationGateway
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
     * @param string $vacationId
     * @return PublicHoliday
     */
    public function get($vacationId)
    {
        if (($vacation = $this->entityRepository->find($vacationId)) === null) {
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
        $query = $this->entityManager->getConnection()->query(
            "SELECT DISTINCT(YEAR(v_start)) FROM vacation"
        );
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_COLUMN);
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
        $interval = $month ? 'month' : 'year';
        $month = $month ?: 1;
        $date = new \DateTimeImmutable("01.$month.$year");
        $start = $date->modify("first day of this $interval");
        $end = $date->modify("first day of next $interval");

        $vacations = $this->getVacationsInRange($start, $end);

        $userVacationDays = array();
        foreach ($vacations as $vacation) {
            if (!isset($userVacationDays[$vacation->user->login])) {
                $userVacationDays[$vacation->user->login] = new DaySet();
            }

            $userVacationDays[$vacation->user->login] = $userVacationDays[$vacation->user->login]->merge(
                DaySet::createFromRange(
                    Day::fromDateTime($vacation->start),
                    Day::fromDateTime($vacation->end)
                )->filter(
                    function (Day $day) use ($start, $end) {
                        return ($day >= $start) && ($day < $end);
                    }
                )
            );
        }

        return $userVacationDays;
    }

    /**
     * Get vacations
     *
     * @param int $year
     * @return Vacation[]
     */
    public function getVacations($year = null)
    {
        $start = $end = null;
        if ($year) {
            $date = new \DateTimeImmutable("01.01.$year");

            $start = $date->modify("first day of this year");
            $end = $date->modify("first day of next year");
        }

        return $this->getVacationsInRange($start, $end);
    }

    /**
     * Get vacations in range
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @return Vacation[]
     */
    protected function getVacationsInRange(\DateTimeInterface $start = null, \DateTimeInterface $end = null)
    {
        $whereCondition = null;
        if ($start) {
            $whereCondition = "WHERE (v.start >= :start AND v.start < :end) OR (v.end >= :start AND v.end < :end)";
        }

        $query = $this->entityManager->createQuery(
            "SELECT v FROM Qafoo\TimePlannerBundle\Domain\Vacation v $whereCondition ORDER BY v.start"
        );

        if ($whereCondition) {
            $query->setParameter('start', $start);
            $query->setParameter('end', $end);
        }

        return $query->getResult();
    }

    /**
     * Store
     *
     * @param Vacation $vacation
     * @return Vacation
     */
    public function store(Vacation $vacation)
    {
        $this->entityManager->persist($vacation);
        $this->entityManager->flush();

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
        $this->entityManager->remove($vacation);
        $this->entityManager->flush();
    }
}

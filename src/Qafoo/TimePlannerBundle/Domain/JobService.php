<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Qafoo\TimePlannerBundle\Gateway\JobGateway;

class JobService
{
    /**
     * Job gateway
     *
     * @var JobGateway
     */
    private $jobGateway;

    /**
     * Public holiday service
     *
     * @var PublicHolidayService
     */
    private $publicHolidayService;

    /**
     * Expected sick leave [0 … 1]
     *
     * @var float
     */
    private $expectedSickLeave;

    public function __construct(JobGateway $jobGateway, PublicHolidayService $publicHolidayService, $expectedSickLeave)
    {
        $this->jobGateway = $jobGateway;
        $this->publicHolidayService = $publicHolidayService;
        $this->expectedSickLeave = $expectedSickLeave;
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
        return $this->jobGateway->getJobs($year, $month);
    }

    /**
     * Get work days
     *
     * @param int $year
     * @param int $month
     * @return DaySet
     */
    public function getWorkDays($year, $month)
    {
        $workDays = DaySet::createFromRange(
            $start = new Day("1.$month.$year"),
            $start->modify("last day of this month")
        )->filter(
            function (Day $day) {
                return !$day->isWeekend();
            }
        );
        return $workDays->diff($this->publicHolidayService->getHolidayDays($year));
    }

    /**
     * Get available work days
     *
     * @param int $year
     * @param int $month
     * @return int
     */
    public function getAvailableWorkDays($year, $month)
    {
        return floor(count($this->getWorkDays($year, $month)) * (1 - $this->expectedSickLeave));
    }

    /**
     * Store
     *
     * @param Job $job
     * @return Job
     */
    public function store(Job $job)
    {
        return $this->jobGateway->store($job);
    }

    /**
     * Remove
     *
     * @param Job $job
     * @return void
     */
    public function remove(Job $job)
    {
        return $this->jobGateway->remove($job);
    }
}

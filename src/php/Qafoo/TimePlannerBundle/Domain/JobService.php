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
     * Revenue calculator
     *
     * @var RevenueCalculator
     */
    private $revenueCalculator;

    /**
     * Expected sick leave [0 … 1]
     *
     * @var float
     */
    private $expectedSickLeave;

    public function __construct(
        JobGateway $jobGateway,
        PublicHolidayService $publicHolidayService,
        RevenueCalculator $revenueCalculator,
        $expectedSickLeave = .1
    ) {
        $this->jobGateway = $jobGateway;
        $this->publicHolidayService = $publicHolidayService;
        $this->revenueCalculator = $revenueCalculator;
        $this->expectedSickLeave = (float) $expectedSickLeave;
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
        $jobs = $this->jobGateway->getJobs($year, $month);
        foreach ($jobs as $job) {
            $job->calculatedRevenue = $this->revenueCalculator->calculate($job);
        }

        return $jobs;
    }

    /**
     * Get customers
     *
     * @return string[]
     */
    public function getCustomers()
    {
        return $this->jobGateway->getCustomers();
    }

    /**
     * Get projects
     *
     * @return string[]
     */
    public function getProjects()
    {
        return $this->jobGateway->getProjects();
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
     * Calculate sum
     *
     * @param Job[] $jobs
     * @return Job
     */
    public function calculateSum(array $jobs)
    {
        $sum = new Job();
        foreach ($jobs as $job) {
            $sum->personDays->minimum += $job->personDays->minimum;
            $sum->personDays->maximum += $job->personDays->maximum;
            $sum->calculatedRevenue += $job->calculatedRevenue;

            foreach ($job->assignees as $assignment) {
                if (!isset($sum->assignees[$assignment->user])) {
                    $sum->assignees[$assignment->user] = new Job\Assignment($assignment->user);
                }

                $sum->assignees[$assignment->user]->days += $assignment->days;
            }
        }

        return $sum;
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

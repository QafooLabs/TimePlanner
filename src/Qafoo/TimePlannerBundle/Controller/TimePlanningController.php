<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use QafooLabs\MVC\TokenContext;
use QafooLabs\MVC\RedirectRouteResponse;

use Qafoo\TimePlannerBundle\Domain\JobService;
use Qafoo\TimePlannerBundle\Domain\Job;
use Qafoo\TimePlannerBundle\Domain\MetaData;
use Qafoo\TimePlannerBundle\Controller\TimePlanning\Overview;
use Qafoo\TimePlannerBundle\Controller\TimePlanning\Edit;

class TimePlanningController extends Controller
{
    public function indexAction(Request $request, TokenContext $context)
    {
        $year = $request->get('year', date("Y"));
        $month = $request->get('month', date("n"));

        $jobService = $this->get('qafoo.time_planner.domain.job_service');
        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');
        $userService = $this->get('qafoo.user.domain.user_service');

        return new Overview(
            array(
                'year' => $year,
                'month' => $month,
                'user' => $context->getCurrentUser(),
                'users' => $userService->getAllUsers(),
                'availableWorkDays' => $jobService->getAvailableWorkDays($year, $month),
                'vacationDays' => $vacationService->getVacationDaysPerUser($year, $month),
                'jobs' => $jobs = $jobService->getJobs($year, $month),
                'sum' => $jobService->calculateSum($jobs),
                'highlight' => $request->get('highlight', null),
            )
        );
    }

    public function editAction(Job $job = null)
    {
        return new Edit(
            array(
                'job' => $job,
            )
        );
    }

    public function removeAction(Request $request, Job $job)
    {
        $year = $request->get('year', date("Y"));
        $month = $request->get('month', date("n"));

        $jobService = $this->get('qafoo.time_planner.domain.job_service');
        $jobService->remove($job);

        return new RedirectRouteResponse(
            'qafoo.time_planner.time_planning.overview',
            array(
                'year' => $year,
                'month' => $month,
            )
        );
    }

    public function assignAction(Request $request, Job $job)
    {
        $year = $request->get('year', date("Y"));
        $month = $request->get('month', date("n"));

        $userService = $this->get('qafoo.user.domain.user_service');
        $assignees = $request->get('assignees', array());
        foreach ($assignees as $user => $days) {
            $job->assignees[$user] = new Job\Assignment(
                $userService->getUserByLogin($user)->login,
                $days
            );
        }
        $jobService = $this->get('qafoo.time_planner.domain.job_service');
        $jobService->store($job);

        return new RedirectRouteResponse(
            'qafoo.time_planner.time_planning.overview',
            array(
                'year' => $year,
                'month' => $month,
            )
        );
    }

    public function assignInvoiceAction(Request $request, Job $job)
    {
        $year = $request->get('year', date("Y"));
        $month = $request->get('month', date("n"));

        $jobService = $this->get('qafoo.time_planner.domain.job_service');
        $job->invoiceId = $request->get('invoiceId', null);
        $jobService->store($job);

        return new RedirectRouteResponse(
            'qafoo.time_planner.time_planning.overview',
            array(
                'year' => $year,
                'month' => $month,
            )
        );
    }

    public function storeAction(Request $request, TokenContext $context, Job $job = null)
    {
        $year = $request->get('year', date("Y"));
        $month = $request->get('month', date("n"));

        $job = $job ?: new Job();
        $job->month = new \DateTime("$year-$month-01");
        $job->customer = $request->get('customer');
        $job->project = $request->get('project');
        $job->personDays = new Job\PersonDays(
            $request->get('pt_min'),
            $request->get('pt_max')
        );
        $job->expectedRevenue = $request->get('revenue');
        $job->comment = $request->get('comment');
        $job->metaData = new MetaData($context->getCurrentUser());

        $jobService = $this->get('qafoo.time_planner.domain.job_service');
        $jobService->store($job);

        return new RedirectRouteResponse(
            'qafoo.time_planner.time_planning.overview',
            array(
                'year' => $year,
                'month' => $month,
            )
        );
    }
}

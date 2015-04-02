<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use QafooLabs\MVC\TokenContext;
use QafooLabs\MVC\RedirectRouteResponse;

use Qafoo\TimePlannerBundle\Domain\JobService;
use Qafoo\TimePlannerBundle\Domain\Job;
use Qafoo\TimePlannerBundle\Controller\TimePlanning\Overview;
use Qafoo\TimePlannerBundle\Controller\TimePlanning\Edit;

class TimePlanningController extends Controller
{
    public function indexAction($year = null, $month = null)
    {
        $year = $year ?: date("Y");
        $month = $month ?: date("m");
        $jobService = $this->get('qafoo.time_planner.domain.job_service');

        return new Overview(
            array(
                'jobs' => $jobService->getJobs($year, $month),
                'year' => $year,
                'month' => $month,
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

    public function removeAction(Job $job)
    {
        $jobService = $this->get('qafoo.time_planner.domain.job_service');
        $jobService->remove($job);

        return new RedirectRouteResponse('qafoo.time_planner.time_planning.overview');
    }

    public function storeAction(Request $request, Job $job = null)
    {
        $job = $job ?: new Job();
        $job->name = $request->get('name');
        $job->date = new \DateTime($request->get('start'));

        $jobService = $this->get('qafoo.time_planner.domain.job_service');
        $jobService->store($job);

        return new RedirectRouteResponse('qafoo.time_planner.time_planning.overview');
    }
}
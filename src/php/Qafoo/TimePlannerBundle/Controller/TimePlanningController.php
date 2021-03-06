<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
                'users' => $userService->findUsers(),
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

        try {
            $jobService = $this->get('qafoo.time_planner.domain.job_service');
            $jobService->remove($job);
            $request->getSession()->getFlashBag()->add('success', 'Job removed');
        } catch (\Exception $e) {
            $request->getSession()->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectRouteResponse(
            'qafoo.time_planner.time_planning.overview',
            array(
                'year' => $year,
                'month' => $month,
            )
        );
    }

    public function assignAction(Request $request, TokenContext $context, Job $job)
    {
        $userService = $this->get('qafoo.user.domain.user_service');
        $assignees = $request->get('assignees', array());
        foreach ($assignees as $user => $days) {
            $job->assignees[$user] = new Job\Assignment(
                $userService->getUserByLogin($user)->login,
                $days
            );
        }
        $job->revision = $request->get('revision', null);
        $job->metaData = MetaData::fromContext($context);

        return $this->storeAndRedirect($request, $job);
    }

    public function assignInvoiceAction(Request $request, TokenContext $context, Job $job)
    {
        $job->revision = $request->get('revision', null);
        $job->invoiceId = $request->get('invoiceId', null);
        $job->metaData = MetaData::fromContext($context);

        return $this->storeAndRedirect($request, $job);
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
        $job->metaData = MetaData::fromContext($context);

        return $this->storeAndRedirect($request, $job);
    }

    public function importAction(Request $request)
    {
        $year = $request->get('year', date("Y"));
        $month = $request->get('month', date("n"));

        try {
            $importFile = $request->files->get('import');
            if (!in_array($importFile->getMimeType(), array('text/plain', 'text/csv'))) {
                throw new \Exception(
                    'Invalid mime type – expected text/csv, got ' . $importFile->getMimeType()
                );
            }

            $importFile = $importFile->move(
                $this->container->getParameter('kernel.cache_dir'),
                md5(microtime()) . '.' . $importFile->getExtension()
            );

            $jobImporter = $this->get('qafoo.time_planner.domain.job_csv_importer');
            $jobs = $jobImporter->import("$year-$month", $importFile->getPathname());

            $request->getSession()->getFlashBag()->add('success', 'Imported ' . count($jobs) . ' jobs.');
        } catch (\Exception $e) {
            $request->getSession()->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectRouteResponse(
            'qafoo.time_planner.time_planning.overview',
            array(
                'year' => $year,
                'month' => $month,
            )
        );
    }

    /**
     * storeAndRedirect
     *
     * @param Request $request
     * @param Job $job
     * @return void
     */
    protected function storeAndRedirect(Request $request, Job $job)
    {
        try {
            $jobService = $this->get('qafoo.time_planner.domain.job_service');
            $jobService->store($job);
            $request->getSession()->getFlashBag()->add('success', 'Job updated');
        } catch (\Exception $e) {
            $request->getSession()->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectRouteResponse(
            'qafoo.time_planner.time_planning.overview',
            array(
                'year' => $job->month->format('Y'),
                'month' => $job->month->format('n'),
                'highlight' => $job->jobId,
            )
        );
    }

    public function customersAction()
    {
        $jobService = $this->get('qafoo.time_planner.domain.job_service');
        return new JsonResponse(
            $jobService->getCustomers()
        );
    }

    public function projectsAction()
    {
        $jobService = $this->get('qafoo.time_planner.domain.job_service');
        return new JsonResponse(
            $jobService->getProjects()
        );
    }
}

<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use QafooLabs\MVC\TokenContext;
use QafooLabs\MVC\RedirectRouteResponse;

use Qafoo\TimePlannerBundle\Domain\Job;
use Qafoo\TimePlannerBundle\Domain\Vacation;
use Qafoo\TimePlannerBundle\Controller\Overview\Overview;

class OverviewController extends Controller
{
    public function indexAction(TokenContext $context)
    {
        $currentUser = $context->getCurrentUser();
        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');
        $availableVacation = $this->get('qafoo.time_planner.gateway.available_vacation');
        $metaDataService = $this->get('qafoo.time_planner.domain.meta_data_service');

        return new Overview(
            array(
                'user' => $currentUser,
                'remainingVacation' => $availableVacation->getAvailableVacationDays($currentUser->login, date('Y')) -
                    count($vacationService->getVacationDays($currentUser, date('Y'))),
                'vacations' => $metaDataService->getLastEdits(Vacation::CLASS),
                'jobs' => $metaDataService->getLastEdits(Job::CLASS),
            )
        );
    }
}

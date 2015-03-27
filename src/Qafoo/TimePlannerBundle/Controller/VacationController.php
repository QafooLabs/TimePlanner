<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use QafooLabs\MVC\TokenContext;

use Qafoo\TimePlannerBundle\Domain\VacationService;

class VacationController extends Controller
{
    public function indexAction(TokenContext $context)
    {
        $currentUser = $context->getCurrentUser();
        $vacationService = $this->get('qafoo_time_planner.domain.vacation_service');

        return new View\Overview(
            array(
                'user' => $currentUser,
                'remainingVacation' => $vacationService->getRemainingVacationDays($currentUser),
                'vacations' => $vacationService->getNextVacations(10),
            )
        );
    }
}

<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use QafooLabs\MVC\TokenContext;

use Qafoo\TimePlannerBundle\Domain\VacationService;
use Qafoo\TimePlannerBundle\Domain\Vacation;

class VacationController extends Controller
{
    public function indexAction(TokenContext $context)
    {
        $currentUser = $context->getCurrentUser();
        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');

        return new View\Overview(
            array(
                'user' => $currentUser,
                'remainingVacation' => $vacationService->getRemainingVacationDays($currentUser, date('Y')),
                'vacations' => $vacationService->getNextVacations(10),
            )
        );
    }

    public function editAction(TokenContext $context, Vacation $vacation = null)
    {
        $currentUser = $context->getCurrentUser();
        $userService = $this->get('qafoo.user.domain.user_service');

        return new View\Edit(
            array(
                'user' => $currentUser,
                'users' => $userService->getAllUsers(),
                'vacation' => $vacation,
            )
        );
    }
}

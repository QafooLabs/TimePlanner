<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use QafooLabs\MVC\TokenContext;
use QafooLabs\MVC\RedirectRouteResponse;

use Qafoo\TimePlannerBundle\Domain\VacationService;
use Qafoo\TimePlannerBundle\Domain\Vacation;

class VacationController extends Controller
{
    public function indexAction(TokenContext $context)
    {
        $currentUser = $context->getCurrentUser();
        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');

        return new Vacation\Overview(
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

        return new Vacation\Edit(
            array(
                'user' => $currentUser,
                'users' => $userService->getAllUsers(),
                'vacation' => $vacation,
            )
        );
    }

    public function storeAction(Request $request, TokenContext $context, Vacation $vacation = null)
    {
        $userService = $this->get('qafoo.user.domain.user_service');
        $user = $userService->getUserByLogin($request->get('user'));

        $vacation = $vacation ?: new Vacation();
        $vacation->start = new \DateTime($request->get('start'));
        $vacation->end = new \DateTime($request->get('end'));
        $vacation->user = $user;
        $vacation->comment = $request->get('comment', null);

        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');
        $vacationService->store($vacation);

        return new RedirectRouteResponse('qafoo.time_planner.vacation.overview');
    }
}

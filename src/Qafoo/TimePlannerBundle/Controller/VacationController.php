<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use QafooLabs\MVC\TokenContext;
use QafooLabs\MVC\RedirectRouteResponse;

use Qafoo\TimePlannerBundle\Domain\VacationService;
use Qafoo\TimePlannerBundle\Domain\Vacation;
use Qafoo\TimePlannerBundle\Controller\Vacation\Overview;
use Qafoo\TimePlannerBundle\Controller\Vacation\Edit;

class VacationController extends Controller
{
    public function indexAction(TokenContext $context, $year = null)
    {
        $year = $year ?: date("Y");
        $currentUser = $context->getCurrentUser();
        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');
        $availableVacation = $this->get('qafoo.time_planner.gateway.available_vacation');

        return new Overview(
            array(
                'user' => $currentUser,
                'year' => $year,
                'years' => $vacationService->getYears(),
                'remainingVacation' => $availableVacation->getAvailableVacationDays($currentUser->login, $year) -
                    count($vacationService->getVacationDays($currentUser, $year)),
                'vacations' => $vacationService->getVacations($year),
            )
        );
    }

    public function listAction()
    {
        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');
        $vacations = $vacationService->getVacations();
        $calendar = new \Sabre\VObject\Component\VCalendar();
        foreach ($vacations as $vacation) {
            $calendar->add(
                'VEVENT',
                array(
                    'SUMMARY' => 'Vacation: ' . $vacation->user->getUsername() .
                        ($vacation->comment ? ' (' . $vacation->comment . ')' : ''),
                    'DTSTART' => $vacation->start->setTimezone(new \DateTimeZone('UTC'))->modify('today'),
                    'DTEND' => $vacation->end->setTimezone(new \DateTimeZone('UTC'))->modify('tomorrow'),
                )
            );
        }

        return new Response(
            $calendar->serialize(),
            200,
            array(
                'Content-Type' => 'text/calendar',
            )
        );
    }

    public function editAction(TokenContext $context, Vacation $vacation = null)
    {
        $currentUser = $context->getCurrentUser();
        $userService = $this->get('qafoo.user.domain.user_service');

        return new Edit(
            array(
                'user' => $currentUser,
                'users' => $userService->getAllUsers(),
                'vacation' => $vacation,
            )
        );
    }

    public function removeAction(Vacation $vacation)
    {
        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');
        $vacationService->remove($vacation);

        return new RedirectRouteResponse('qafoo.time_planner.vacation.overview');
    }

    public function storeAction(Request $request, TokenContext $context, Vacation $vacation = null)
    {
        $userService = $this->get('qafoo.user.domain.user_service');

        $vacation = $vacation ?: new Vacation();
        $vacation->start = new \DateTime($request->get('start'));
        $vacation->end = new \DateTime($request->get('end'));
        $vacation->user = $userService->getUserByLogin($request->get('user'));
        $vacation->comment = $request->get('comment', null);

        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');
        $vacationService->store($vacation);

        return new RedirectRouteResponse('qafoo.time_planner.vacation.overview');
    }
}

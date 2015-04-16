<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use QafooLabs\MVC\TokenContext;
use QafooLabs\MVC\RedirectRouteResponse;

use Qafoo\TimePlannerBundle\Domain\VacationService;
use Qafoo\TimePlannerBundle\Domain\Vacation;
use Qafoo\TimePlannerBundle\Domain\MetaData;
use Qafoo\TimePlannerBundle\Domain\DaySet;
use Qafoo\TimePlannerBundle\Controller\Vacation\Overview;
use Qafoo\TimePlannerBundle\Controller\Vacation\AvailableVacation;
use Qafoo\TimePlannerBundle\Controller\Vacation\Edit;

class VacationController extends Controller
{
    public function indexAction(Request $request, TokenContext $context, $year = null)
    {
        $year = $year ?: date("Y");
        $currentUser = $context->getCurrentUser();
        $userService = $this->get('qafoo.user.domain.user_service');
        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');
        $availableVacation = $this->get('qafoo.time_planner.gateway.available_vacation');

        $remainingVacation = $vacationService->getVacationDaysPerUser($year);
        foreach ($userService->findUsers() as $user) {
            $remainingVacation[$user->login] = new AvailableVacation(
                array(
                    'user' => $user,
                    'available' => $availableVacation->getAvailableVacationDays($user->login, $year),
                    'booked' => isset($remainingVacation[$user->login]) ?
                        $remainingVacation[$user->login] :
                        new DaySet(),
                )
            );
        }

        return new Overview(
            array(
                'user' => $currentUser,
                'year' => $year,
                'years' => $vacationService->getYears(),
                'remainingVacation' => $remainingVacation,
                'vacations' => $vacationService->getVacations($year),
                'highlight' => $request->get('highlight', null),
            )
        );
    }

    public function listAction()
    {
        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');
        $vacations = $vacationService->getVacations();
        $calendar = new \Sabre\VObject\Component\VCalendar();
        foreach ($vacations as $vacation) {
            $event = $calendar->add('VEVENT');
            $event->add(
                'SUMMARY',
                'Vacation: ' . $vacation->user->getUsername() .
                ($vacation->comment ? ' (' . $vacation->comment . ')' : '')
            );
            $event->add('DTSTART', $vacation->start->modify('today'))['VALUE'] = 'DATE';
            $event->add('DTEND', $vacation->start->modify('tomorrow'))['VALUE'] = 'DATE';
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
                'users' => $userService->findUsers(),
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
        $vacation->metaData = new MetaData($context->getCurrentUser());

        $vacationService = $this->get('qafoo.time_planner.domain.vacation_service');
        $vacationService->store($vacation);

        return new RedirectRouteResponse('qafoo.time_planner.vacation.overview');
    }
}

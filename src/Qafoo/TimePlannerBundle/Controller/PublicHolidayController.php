<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use QafooLabs\MVC\TokenContext;
use QafooLabs\MVC\RedirectRouteResponse;

use Qafoo\TimePlannerBundle\Domain\PublicHolidayService;
use Qafoo\TimePlannerBundle\Domain\PublicHoliday;
use Qafoo\TimePlannerBundle\Controller\PublicHoliday\Overview;
use Qafoo\TimePlannerBundle\Controller\PublicHoliday\Edit;

class PublicHolidayController extends Controller
{
    public function indexAction()
    {
        $holidayService = $this->get('qafoo.time_planner.domain.public_holiday_service');

        return new Overview(
            array(
                'holidays' => $holidayService->getHolidays(date("Y")),
                'years' => $holidayService->getYears(),
            )
        );
    }

    public function editAction(PublicHoliday $publicHoliday = null)
    {
        return new Edit(
            array(
                'holiday' => $publicHoliday,
            )
        );
    }

    public function storeAction(Request $request, PublicHoliday $publicHoliday = null)
    {
        $publicHoliday = $publicHoliday ?: new PublicHoliday();
        $publicHoliday->name = $request->get('name');
        $publicHoliday->date = new \DateTime($request->get('start'));

        $publicHolidayService = $this->get('qafoo.time_planner.domain.public_holiday_service');
        $publicHolidayService->store($publicHoliday);

        return new RedirectRouteResponse('qafoo.time_planner.public_holiday.overview');
    }
}

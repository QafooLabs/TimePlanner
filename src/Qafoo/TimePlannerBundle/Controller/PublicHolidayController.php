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

    public function editAction(PublicHoliday $holiday = null)
    {
        return new Edit(
            array(
                'holiday' => $holiday,
            )
        );
    }

    public function storeAction(Request $request, PublicHoliday $holiday = null)
    {
        $holiday = $holiday ?: new PublicHoliday();
        $holiday->name = $request->get('name');
        $holiday->date = new \DateTime($request->get('start'));

        $holidayService = $this->get('qafoo.time_planner.domain.public_holiday_service');
        $holidayService->store($holiday);

        return new RedirectRouteResponse('qafoo.time_planner.public_holiday.overview');
    }
}

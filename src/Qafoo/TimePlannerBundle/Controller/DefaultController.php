<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use QafooLabs\MVC\TokenContext;
use QafooLabs\MVC\RedirectRouteResponse;

use Qafoo\TimePlannerBundle\Domain\VacationService;
use Qafoo\TimePlannerBundle\Domain\Vacation;

class DefaultController extends Controller
{
    public function indexAction(TokenContext $context)
    {
        return null;
    }
}

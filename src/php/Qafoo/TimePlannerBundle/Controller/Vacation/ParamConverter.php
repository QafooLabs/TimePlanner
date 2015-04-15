<?php

namespace Qafoo\TimePlannerBundle\Controller\Vacation;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter as Configuration;
use Symfony\Component\HttpFoundation\Request;

use Qafoo\TimePlannerBundle\Gateway\VacationGateway;

class ParamConverter implements ParamConverterInterface
{
    /**
     * Gateway
     *
     * @var VacationGateway
     */
    private $gateway;

    public function __construct(VacationGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function apply(Request $request, Configuration $configuration)
    {
        $vacationId = $request->attributes->get('vacation');
        if (!$vacationId) {
            return false;
        }

        $vacation = $this->gateway->get($vacationId);

        $parameterName = $configuration->getName();
        $request->attributes->set($parameterName, $vacation);

        return true;
    }

    public function supports(Configuration $configuration)
    {
        return "Qafoo\\TimePlannerBundle\\Domain\\Vacation" === $configuration->getClass();
    }
}

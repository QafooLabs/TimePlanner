<?php

namespace Qafoo\TimePlannerBundle\Controller\PublicHoliday;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter as Configuration;
use Symfony\Component\HttpFoundation\Request;

use Qafoo\TimePlannerBundle\Gateway\PublicHolidayGateway;

class ParamConverter implements ParamConverterInterface
{
    /**
     * Gateway
     *
     * @var PublicHolidayGateway
     */
    private $gateway;

    public function __construct(PublicHolidayGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function apply(Request $request, Configuration $configuration)
    {
        $publicHolidayId = $request->attributes->get('publicHoliday');
        if (!$publicHolidayId) {
            return false;
        }

        $publicHoliday = $this->gateway->get($publicHolidayId);

        $parameterName = $configuration->getName();
        $request->attributes->set($parameterName, $publicHoliday);

        return true;
    }

    public function supports(Configuration $configuration)
    {
        return "Qafoo\\TimePlannerBundle\\Domain\\PublicHoliday" === $configuration->getClass();
    }
}

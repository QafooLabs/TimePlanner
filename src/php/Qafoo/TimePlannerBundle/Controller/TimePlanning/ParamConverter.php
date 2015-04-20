<?php

namespace Qafoo\TimePlannerBundle\Controller\TimePlanning;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter as Configuration;
use Symfony\Component\HttpFoundation\Request;

use Qafoo\TimePlannerBundle\Gateway\JobGateway;

class ParamConverter implements ParamConverterInterface
{
    /**
     * Gateway
     *
     * @var JobGateway
     */
    private $gateway;

    public function __construct(JobGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function apply(Request $request, Configuration $configuration)
    {
        $jobId = $request->attributes->get('job');
        if (!$jobId) {
            return false;
        }

        $job = $this->gateway->get($jobId);
        $job->revision = $request->get('revision', $job->revision);

        $parameterName = $configuration->getName();
        $request->attributes->set($parameterName, $job);

        return true;
    }

    public function supports(Configuration $configuration)
    {
        return "Qafoo\\TimePlannerBundle\\Domain\\Job" === $configuration->getClass();
    }
}

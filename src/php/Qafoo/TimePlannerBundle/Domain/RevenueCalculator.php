<?php

namespace Qafoo\TimePlannerBundle\Domain;

abstract class RevenueCalculator
{
    /**
     * Calculate revenue from job data
     *
     * @param Job $job
     * @return number
     */
    abstract public function calculate(Job $job);
}

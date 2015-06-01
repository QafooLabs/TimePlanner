<?php

namespace Qafoo\TimePlannerBundle\Domain\RevenueCalculator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

use Qafoo\TimePlannerBundle\Domain\RevenueCalculator;
use Qafoo\TimePlannerBundle\Domain\Job;

class SymfonyExpressionLanguage extends RevenueCalculator
{
    /**
     * Expression language
     *
     * @var ExpressionLanguage
     */
    private $expressionLanguage;

    /**
     * @param mixed $expressionLanguage
     * @return void
     */
    public function __construct(ExpressionLanguage $expressionLanguage)
    {
        $this->expressionLanguage = $expressionLanguage;
        $this->expressionLanguage->register(
            'min',
            function ($arguments) {
                return "min($arguments)";
            },
            function ($variables) {
                return call_user_func_array('min', array_slice(func_get_args(), 1));
            }
        );
        $this->expressionLanguage->register(
            'max',
            function ($arguments) {
                return "max($arguments)";
            },
            function ($variables) {
                return call_user_func_array('max', array_slice(func_get_args(), 1));
            }
        );
    }

    /**
     * Calculate revenue from job data
     *
     * @param Job $job
     * @return number
     */
    public function calculate(Job $job)
    {
        try {
            return $this->expressionLanguage->evaluate(
                $job->expectedRevenue,
                array(
                    'days' => $job->personDays->minimum,
                    'booked' => array_sum(
                        array_map(
                            function (Job\Assignment $assignment) {
                                return $assignment->days;
                            },
                            is_object($job->assignees) ? $job->assignees->toArray() : $job->assignees
                        )
                    )
                )
            );
        } catch (SyntaxError $e) {
            return 0;
        }
    }
}

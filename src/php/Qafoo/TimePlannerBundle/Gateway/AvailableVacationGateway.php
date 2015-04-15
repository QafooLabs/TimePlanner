<?php

namespace Qafoo\TimePlannerBundle\Gateway;

class AvailableVacationGateway
{
    /**
     * Default vacation days
     *
     * @var int
     */
    private $defaultVacationDays;

    /**
     * Vacation day overrides
     *
     * @var array
     */
    private $vacationDayOverrides = array();

    /**
     * @param int $defaultVacationDays
     * @param array $vacationDayOverrides
     * @return void
     */
    public function __construct($defaultVacationDays = 30, array $vacationDayOverrides = array())
    {
        $this->defaultVacationDays = $defaultVacationDays;
        $this->vacationDayOverrides = $vacationDayOverrides;
    }

    /**
     * Get available vacation days
     *
     * @param string $user
     * @paran int $year
     * @return int
     */
    public function getAvailableVacationDays($user, $year)
    {
        if (isset($this->vacationDayOverrides[$year]) &&
            isset($this->vacationDayOverrides[$year][$user])) {
            return $this->vacationDayOverrides[$year][$user];
        }

        return $this->defaultVacationDays;
    }
}

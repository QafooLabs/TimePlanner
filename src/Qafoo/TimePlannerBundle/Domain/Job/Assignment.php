<?php

namespace Qafoo\TimePlannerBundle\Domain\Job;

use Kore\DataObject\DataObject;

use Qafoo\UserBundle\Domain\User;

class Assignment extends DataObject
{
    /**
     * User
     *
     * @var string
     */
    public $user;

    /**
     * Assigned days
     *
     * @var int
     */
    public $days;

    public function __construct($user = null, $days = 0)
    {
        $this->user = $user;
        $this->days = $days;
    }
}

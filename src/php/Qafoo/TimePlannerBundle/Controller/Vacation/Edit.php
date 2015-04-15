<?php

namespace Qafoo\TimePlannerBundle\Controller\Vacation;

use Kore\DataObject\DataObject;

class Edit extends DataObject
{
    /**
     * User
     *
     * @var User
     */
    public $user;

    /**
     * All users
     *
     * @var User[]
     */
    public $users = array();

    /**
     * Vacation
     *
     * @var Vacation
     */
    public $vacation;
}

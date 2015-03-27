<?php

namespace Qafoo\TimePlannerBundle\Controller\View;

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

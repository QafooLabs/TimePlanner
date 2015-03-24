<?php

namespace Qafoo\UserBundle\Domain;

use Kore\DataObject\DataObject;

class User extends DataObject
{
    /**
     * Login
     *
     * @var string
     */
    public $login;

    /**
     * Password
     *
     * @var string
     */
    public $password;

    /**
     * Name
     *
     * @var Name
     */
    public $name;

    /**
     * E-Mail
     *
     * @var Email
     */
    public $email;
}

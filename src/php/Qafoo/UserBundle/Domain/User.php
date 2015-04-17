<?php

namespace Qafoo\UserBundle\Domain;

use Qafoo\UserBundle\Domain\User\Authentication;

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
     * Authentication information
     *
     * @var Authentication
     */
    public $auth;

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

    /**
     * @param array $values
     * @return void
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->auth = $this->auth ?: new Authentication();
        $this->name = $this->name ?: new Name();
        $this->email = $this->email ?: new EMail();
    }
}

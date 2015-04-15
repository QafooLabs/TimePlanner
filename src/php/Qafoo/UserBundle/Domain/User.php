<?php

namespace Qafoo\UserBundle\Domain;

use Qafoo\UserBundle\Domain\User\Authentification;

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
     * Authentification information
     *
     * @var Authentification
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

        $this->auth = $this->auth ?: new Authentification();
        $this->name = $this->name ?: new Name();
        $this->email = $this->email ?: new EMail();
    }
}

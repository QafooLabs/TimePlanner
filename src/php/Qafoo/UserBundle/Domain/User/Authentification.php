<?php

namespace Qafoo\UserBundle\Domain\User;

use Kore\DataObject\DataObject;

class Authentification extends DataObject
{
    /**
     * Password
     *
     * @var string
     */
    public $password;

    /**
     * Salt
     *
     * @var string
     */
    public $salt;

    /**
     * Confirmation token
     *
     * @var string
     */
    public $confirmationToken;

    /**
     * Password requested at
     *
     * @var \DateTime
     */
    public $requestedAt;

    /**
     * Is enabled
     *
     * @var bool
     */
    public $isEnabled = true;

    /**
     * Is locked
     *
     * @var bool
     */
    public $isLocked = false;

    /**
     * Last login
     *
     * @var \DateTime
     */
    public $lastLogin;

    /**
     * @param array $values
     * @return void
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->salt = $this->salt ?: md5(microtime());
    }
}

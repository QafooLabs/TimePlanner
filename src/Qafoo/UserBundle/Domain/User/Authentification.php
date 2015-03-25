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
     * Confirmation token
     *
     * @var string
     */
    public $confirmationToken;

    /**
     * Salt
     *
     * @var string
     */
    public $salt;

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

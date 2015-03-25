<?php

namespace Qafoo\UserBundle\Domain;

use Kore\DataObject\DataObject;

class EMail extends DataObject
{
    /**
     * E-Mail
     *
     * @var string
     */
    public $email;

    /**
     * @param string $email
     * @return void
     */
    public function __construct($email = '')
    {
        $this->email = $email;
    }

    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->email;
    }
}

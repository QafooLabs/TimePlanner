<?php

namespace Qafoo\UserBundle\Domain;

use Kore\DataObject\DataObject;

class Name extends DataObject
{
    /**
     * First name
     *
     * @var string
     */
    public $firstName;

    /**
     * Intermediate names
     *
     * @var string[]
     */
    public $intermediateNames = array();

    /**
     * Last name
     *
     * @var string
     */
    public $lastName;

    /**
     * @param string $email
     * @return void
     */
    public function __construct($name = null)
    {
        $names = preg_split('(\\s)', $name);

        if (count($names)) {
            $this->lastName = array_pop($names);
        }

        if (count($names)) {
            $this->firstName = array_shift($names);
        }

        $this->intermediateNames = $names;
    }

    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString()
    {
        return implode(
            ' ',
            array_merge(
                array($this->firstName),
                $this->intermediateNames,
                array($this->lastName)
            )
        );
    }
}

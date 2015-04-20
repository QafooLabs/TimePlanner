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
    public function __construct($lastName = null, $firstName = null, array $intermediateNames = array())
    {
        $this->firstName = $firstName;
        $this->intermediateNames = $intermediateNames;
        $this->lastName = $lastName;
    }

    /**
     * Create from name
     *
     * @param string $name
     * @return static
     */
    public static function createFromName($name)
    {
        $names = preg_split('(\\s)', $name);

        if (count($names)) {
            $firstName = array_shift($names);
        }

        $lastName = null;
        if (count($names)) {
            $lastName = array_pop($names);
        }

        $intermediateNames = $names;

        return new static($lastName, $firstName, $intermediateNames);
    }

    /**
     * Get initials
     *
     * @return string
     */
    public function getInitials()
    {
        return iconv_substr($this->firstName, 0, 1, 'UTF-8') .
            iconv_substr($this->lastName, 0, 1, 'UTF-8');
    }

    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString()
    {
        return trim(
            implode(
                ' ',
                array_merge(
                    array($this->firstName),
                    $this->intermediateNames,
                    array($this->lastName)
                )
            )
        );
    }
}

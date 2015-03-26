<?php

namespace Qafoo\UserBundle\Domain;

class NameTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromCommonName()
    {
        $name = new Name("Kore Nordmann");
        $this->assertSame("Kore Nordmann", (string) $name);

        return $name;
    }

    /**
     * @depends testCreateFromCommonName
     */
    public function testParseFirstName(Name $name)
    {
        $this->assertSame("Kore", $name->firstName);
    }

    /**
     * @depends testCreateFromCommonName
     */
    public function testParseEmptyIntermediateNames(Name $name)
    {
        $this->assertSame(array(), $name->intermediateNames);
    }

    /**
     * @depends testCreateFromCommonName
     */
    public function testParseLastName(Name $name)
    {
        $this->assertSame("Nordmann", $name->lastName);
    }
}

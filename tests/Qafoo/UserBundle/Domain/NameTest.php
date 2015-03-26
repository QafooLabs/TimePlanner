<?php

namespace Qafoo\UserBundle\Domain;

/**
 * @covers Qafoo\UserBundle\Domain\Name
 */
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

    public function testCreateFromNameWithMiddleName()
    {
        $name = new Name("Kore Dirk Nordmann");
        $this->assertSame("Kore Dirk Nordmann", (string) $name);

        return $name;
    }

    /**
     * @depends testCreateFromNameWithMiddleName
     */
    public function testParseFirstNameFromMiddleName(Name $name)
    {
        $this->assertSame("Kore", $name->firstName);
    }

    /**
     * @depends testCreateFromNameWithMiddleName
     */
    public function testParseMiddleName(Name $name)
    {
        $this->assertSame(array("Dirk"), $name->intermediateNames);
    }

    /**
     * @depends testCreateFromNameWithMiddleName
     */
    public function testParseLastNameFromMiddleName(Name $name)
    {
        $this->assertSame("Nordmann", $name->lastName);
    }
}

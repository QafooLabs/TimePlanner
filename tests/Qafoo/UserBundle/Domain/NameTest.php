<?php

namespace Qafoo\UserBundle\Domain;

/**
 * @covers Qafoo\UserBundle\Domain\Name
 */
class NameTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromCommonName()
    {
        $name = Name::createFromName("Kore Nordmann");
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

    /**
     * @depends testCreateFromCommonName
     */
    public function testGetInitials(Name $name)
    {
        $this->assertSame("KN", $name->getInitials());
    }

    public function testCreateFromNameWithMiddleName()
    {
        $name = Name::createFromName("Kore Dirk Nordmann");
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

    /**
     * @depends testCreateFromNameWithMiddleName
     */
    public function testGetInitialsWithMiddleName(Name $name)
    {
        $this->assertSame("KN", $name->getInitials());
    }

    public function testCreateFromFirstNameOnly()
    {
        $name = Name::createFromName("Kore");
        $this->assertSame("Kore", (string) $name);

        return $name;
    }

    /**
     * @depends testCreateFromFirstNameOnly
     */
    public function testParseFirstNameFromFirstNameOnly(Name $name)
    {
        $this->assertSame("Kore", $name->firstName);
    }

    /**
     * @depends testCreateFromFirstNameOnly
     */
    public function testParseMiddleNameFromFirstNameOnly(Name $name)
    {
        $this->assertSame(array(), $name->intermediateNames);
    }

    /**
     * @depends testCreateFromFirstNameOnly
     */
    public function testParseLastNameFromFirstNameOnly(Name $name)
    {
        $this->assertNull($name->lastName);
    }

    /**
     * @depends testCreateFromFirstNameOnly
     */
    public function testGetInitialsFromFirstNameOnly(Name $name)
    {
        $this->assertSame("K", $name->getInitials());
    }
}

<?php

namespace Qafoo\UserBundle\Domain;

/**
 * @covers Qafoo\UserBundle\Domain\EMail
 */
class EMailTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromEmail()
    {
        $email = new EMail("kore@example.com");
        $this->assertSame("kore@example.com", (string) $email);

        return $email;
    }
}

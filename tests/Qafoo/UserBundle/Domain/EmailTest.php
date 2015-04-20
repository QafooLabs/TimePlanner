<?php

namespace Qafoo\UserBundle\Domain;

/**
 * @covers Qafoo\UserBundle\Domain\Email
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromEmail()
    {
        $email = new Email("kore@example.com");
        $this->assertSame("kore@example.com", (string) $email);

        return $email;
    }
}

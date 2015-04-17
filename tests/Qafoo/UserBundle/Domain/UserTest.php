<?php

namespace Qafoo\UserBundle\Domain;

/**
 * @covers Qafoo\UserBundle\Domain\User
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthNotEmpty()
    {
        $user = new User();
        $this->assertTrue($user->auth instanceof User\Authentication);
    }

    public function testSetAuth()
    {
        $user = new User(array(
            'auth' => $expectation = new User\Authentication(),
        ));

        $this->assertSame($expectation, $user->auth);
    }

    public function testNameNotEmpty()
    {
        $user = new User();
        $this->assertTrue($user->name instanceof Name);
    }

    public function testSetName()
    {
        $user = new User(array(
            'name' => $expectation = new Name("Kore Nordmann"),
        ));

        $this->assertSame($expectation, $user->name);
    }

    public function testEMailNotEmpty()
    {
        $user = new User();
        $this->assertTrue($user->email instanceof EMail);
    }

    public function testSetEMail()
    {
        $user = new User(array(
            'email' => $expectation = new EMail("kore@example.com"),
        ));

        $this->assertSame($expectation, $user->email);
    }
}

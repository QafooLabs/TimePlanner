<?php

namespace Qafoo\UserBundle\Domain;

/**
 * @covers Qafoo\UserBundle\Domain\FOSUserHelper
 */
class FOSUserHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUsername()
    {
        $user = new FOSUserHelper();
        $user->login = 'kore';

        $this->assertSame('kore', $user->getUsername());
    }

    public function testSetUsername()
    {
        $user = new FOSUserHelper();
        $user->login = 'kore';

        $user->setUsername('updated');

        $this->assertSame('updated', $user->getUsername());
    }

    public function testGetUsernameCanonical()
    {
        $user = new FOSUserHelper();
        $user->login = 'kore';

        $this->assertSame('kore', $user->getUsernameCanonical());
    }

    public function testSetUsernameCanonical()
    {
        $user = new FOSUserHelper();
        $user->login = 'kore';

        $user->setUsernameCanonical('updated');

        // Intentionally ignored
        $this->assertSame('kore', $user->getUsernameCanonical());
    }

    public function testGetEmail()
    {
        $user = new FOSUserHelper();
        $user->email = new EMail('kore@example.com');

        $this->assertSame('kore@example.com', $user->getEmail());
    }

    public function testSetEmail()
    {
        $user = new FOSUserHelper();
        $user->email = new EMail('kore@example.com');

        $user->setEmail('updated@example.com');

        $this->assertSame('updated@example.com', $user->getEmail());
    }

    public function testGetEmailCanonical()
    {
        $user = new FOSUserHelper();
        $user->email = new EMail('kore@example.com');

        $this->assertSame('kore@example.com', $user->getEmailCanonical());
    }

    public function testSetEmailCanonical()
    {
        $user = new FOSUserHelper();
        $user->email = new EMail('kore@example.com');

        $user->setEmailCanonical('updated@example.com');

        // Intentionally ignored
        $this->assertSame('kore@example.com', $user->getEmailCanonical());
    }

    public function testGetPlainPassword()
    {
        $user = new FOSUserHelper();

        $this->assertNull($user->getPlainPassword());
    }

    public function testSetPlainPassword()
    {
        $user = new FOSUserHelper();

        $user->setPlainPassword("password");

        $this->assertSame("password", $user->getPlainPassword());
    }

    public function testEraseCredentials()
    {
        $user = new FOSUserHelper();

        $user->setPlainPassword("password");
        $user->eraseCredentials();

        $this->assertNull($user->getPlainPassword());
    }

    public function testGetPassword()
    {
        $user = new FOSUserHelper();
        $user->login = 'kore';

        $this->assertNull($user->getPassword());
    }

    public function testSetPassword()
    {
        $user = new FOSUserHelper();
        $user->login = 'kore';

        $user->setPassword('updated');

        $this->assertSame('updated', $user->getPassword());
    }

    public function testGetSalt()
    {
        $user = new FOSUserHelper();
        $user->login = 'kore';

        $this->assertNotNull($user->getSalt());
    }

    public function testNoExpirationDatePasswordIsExpired()
    {
        $user = new FOSUserHelper();
        $user->login = 'kore';

        $this->assertFalse($user->isPasswordRequestNonExpired(30));
    }

    public function testExpirationDatePasswordIsExpired()
    {
        $user = new FOSUserHelper();
        $user->login = 'kore';

        $user->setPasswordRequestedAt(new \DateTime("1970-01-01"));

        $this->assertFalse($user->isPasswordRequestNonExpired(30));
    }

    public function testExpirationDatePasswordIsNotExpired()
    {
        $user = new FOSUserHelper();
        $user->login = 'kore';

        $user->setPasswordRequestedAt(new \DateTime("now"));

        $this->assertTrue($user->isPasswordRequestNonExpired(30));
    }
}

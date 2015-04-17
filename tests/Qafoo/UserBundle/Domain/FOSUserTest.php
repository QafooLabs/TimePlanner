<?php

namespace Qafoo\UserBundle\Domain;

/**
 * @covers Qafoo\UserBundle\Domain\FOSUser
 */
class FOSUserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUsername()
    {
        $user = new FOSUser();
        $user->login = 'kore';

        $this->assertSame('kore', $user->getUsername());
    }

    public function testSetUsername()
    {
        $user = new FOSUser();
        $user->login = 'kore';

        $user->setUsername('updated');

        $this->assertSame('updated', $user->getUsername());
    }

    public function testGetUsernameCanonical()
    {
        $user = new FOSUser();
        $user->login = 'kore';

        $this->assertSame('kore', $user->getUsernameCanonical());
    }

    public function testSetUsernameCanonical()
    {
        $user = new FOSUser();
        $user->login = 'kore';

        $user->setUsernameCanonical('updated');

        // Intentionally ignored
        $this->assertSame('kore', $user->getUsernameCanonical());
    }

    public function testGetEmail()
    {
        $user = new FOSUser();
        $user->email = new EMail('kore@example.com');

        $this->assertSame('kore@example.com', $user->getEmail());
    }

    public function testSetEmail()
    {
        $user = new FOSUser();
        $user->email = new EMail('kore@example.com');

        $user->setEmail('updated@example.com');

        $this->assertSame('updated@example.com', $user->getEmail());
    }

    public function testGetEmailCanonical()
    {
        $user = new FOSUser();
        $user->email = new EMail('kore@example.com');

        $this->assertSame('kore@example.com', $user->getEmailCanonical());
    }

    public function testSetEmailCanonical()
    {
        $user = new FOSUser();
        $user->email = new EMail('kore@example.com');

        $user->setEmailCanonical('updated@example.com');

        // Intentionally ignored
        $this->assertSame('kore@example.com', $user->getEmailCanonical());
    }

    public function testGetPlainPassword()
    {
        $user = new FOSUser();

        $this->assertNull($user->getPlainPassword());
    }

    public function testSetPlainPassword()
    {
        $user = new FOSUser();

        $user->setPlainPassword("password");

        $this->assertSame("password", $user->getPlainPassword());
    }

    public function testEraseCredentials()
    {
        $user = new FOSUser();

        $user->setPlainPassword("password");
        $user->eraseCredentials();

        $this->assertNull($user->getPlainPassword());
    }

    public function testGetPassword()
    {
        $user = new FOSUser();
        $user->login = 'kore';

        $this->assertNull($user->getPassword());
    }

    public function testSetPassword()
    {
        $user = new FOSUser();
        $user->login = 'kore';

        $user->setPassword('updated');

        $this->assertSame('updated', $user->getPassword());
    }

    public function testGetSalt()
    {
        $user = new FOSUser();
        $user->login = 'kore';

        $this->assertNotNull($user->getSalt());
    }

    public function testNoExpirationDatePasswordIsExpired()
    {
        $user = new FOSUser();
        $user->login = 'kore';

        $this->assertFalse($user->isPasswordRequestNonExpired(30));
    }

    public function testExpirationDatePasswordIsExpired()
    {
        $user = new FOSUser();
        $user->login = 'kore';

        $user->setPasswordRequestedAt(new \DateTime("1970-01-01"));

        $this->assertFalse($user->isPasswordRequestNonExpired(30));
    }

    public function testExpirationDatePasswordIsNotExpired()
    {
        $user = new FOSUser();
        $user->login = 'kore';

        $user->setPasswordRequestedAt(new \DateTime("now"));

        $this->assertTrue($user->isPasswordRequestNonExpired(30));
    }
}

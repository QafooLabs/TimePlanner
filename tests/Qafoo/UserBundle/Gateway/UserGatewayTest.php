<?php

namespace Qafoo\UserBundle\Gateway;

use Qafoo\UserBundle\Domain\FOSUser;
use Qafoo\UserBundle\Domain\User;
use Qafoo\UserBundle\Domain\Name;
use Qafoo\UserBundle\Domain\EMail;

use Qafoo\IntegrationTest;

/**
 * @covers Qafoo\UserBundle\Gateway\UserGateway
 */
class UserGatewayTest extends IntegrationTest
{
    public function testStoreAndLoadUser()
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $user = new FOSUser();
        $user->login = 'kore';
        $user->name = Name::createFromName("Kore Nordmann");
        $user->email = new EMail("kore@example.com");
        $user->auth->confirmationToken = 'token';

        $userGateway->store($user);
        $loaded = $userGateway->loadUserByUsername($user->login);

        $this->assertEquals($user, $loaded);
        return $user;
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testDoNotFindUser()
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $userGateway->loadUserByUsername("unknown");
    }

    /**
     * @depends testStoreAndLoadUser
     */
    public function testGetAllUsers(FOSUser $user)
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $this->assertEquals(
            array($user),
            $userGateway->getAllUsers()
        );
    }

    /**
     * @depends testStoreAndLoadUser
     */
    public function testFindByEMail(FOSUser $user)
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $this->assertEquals(
            $user,
            $userGateway->findByProperty('email', 'kore@example.com')
        );
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testFailFindByEMail()
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $userGateway->findByProperty('email', 'unknown');
    }

    /**
     * @depends testStoreAndLoadUser
     */
    public function testFindByConfirmationToken(FOSUser $user)
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $this->assertEquals(
            $user,
            $userGateway->findByProperty('token', 'token')
        );
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testFailFindByUnknownProperty()
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $userGateway->findByProperty('unknown', 'unknown');
    }

    public function testSupportsClass()
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $this->assertTrue($userGateway->supportsClass(FOSUser::class));
    }

    public function testNotSupportsClass()
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $this->assertFalse($userGateway->supportsClass(User::class));
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testFailRefreshInvalidUser()
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $user = $this->getMockBuilder('\\FOS\\UserBundle\\Model\\UserInterface')->getMock();
        $userGateway->refreshUser($user);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testFailRefreshUnknownUser()
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');

        $user = new FOSUser();
        $user->login = 'unknown';
        $userGateway->refreshUser($user);
    }

    /**
     * @depends testStoreAndLoadUser
     */
    public function testRefreshUser($user)
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');

        $fake = new FOSUser();
        $fake->login = 'kore';
        $loadedUser = $userGateway->refreshUser($fake);

        $this->assertEquals($user, $loadedUser);
    }

    /**
     * @depends testStoreAndLoadUser
     */
    public function testRemoveUser(FOSUser $user)
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $user = $userGateway->refreshUser($user);

        $userGateway->remove($user);
        $this->assertEquals(
            array(),
            $userGateway->getAllUsers()
        );
    }
}

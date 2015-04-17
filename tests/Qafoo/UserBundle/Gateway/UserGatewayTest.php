<?php

namespace Qafoo\UserBundle\Gateway;

use Qafoo\UserBundle\Domain\FOSUser;
use Qafoo\UserBundle\Domain\Name;
use Qafoo\UserBundle\Domain\Email;

use Qafoo\IntegrationTest;

class UserGatewayTest extends IntegrationTest
{
    public function testStoreAndLoadUser()
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $user = new FOSUser();
        $user->login = 'kore';
        $user->name = new Name("Kore Nordmann");
        $user->email = new Email("kore@example.com");
        $user->auth->confirmationToken = 'token';

        $userGateway->store($user);
        $loaded = $userGateway->loadUserByUsername($user->login);

        $this->assertEquals($user, $loaded);
        return $user;
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
    public function testFindByEmail(FOSUser $user)
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $this->assertEquals(
            $user,
            $userGateway->findByProperty('email', 'kore@example.com')
        );
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
     * @depends testStoreAndLoadUser
     */
    public function testRemoveUser(FOSUser $user)
    {
        $userGateway = $this->getContainer()->get('qafoo.user.gateway.user');
        $userGateway->remove($user);
        $this->assertEquals(
            array(),
            $userGateway->getAllUsers()
        );
    }
}
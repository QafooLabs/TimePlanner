<?php

namespace Qafoo\UserBundle\Domain;

use Qafoo\IntegrationTest;

/**
 * @covers Qafoo\UserBundle\Domain\UserService
 */
class UserServiceTest extends IntegrationTest
{
    public function testCreateUser()
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');

        $this->assertTrue(
            $userService->createUser() instanceof User
        );
    }

    /**
     * Get user properties
     *
     * @return array[]
     */
    public function getUserProperties()
    {
        return array(
            array('login', 'kore'),
            array('email', new EMail('kore@example.com')),
            array('name', new Name('Kore Nordmann')),
        );
    }

    public function testStoreAndRetriveUser()
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');
        $user = $userService->createUser();
        foreach ($this->getUserProperties() as $row) {
            list($property, $value) = $row;
            $user->$property = $value;
        }

        $userService->updateUser($user);

        $loadedUser = $userService->getUserByLogin("kore");
        $this->assertEquals($user, $loadedUser);
        return $loadedUser;
    }

    /**
     * @depends testStoreAndRetriveUser
     * @dataProvider getUserProperties
     */
    public function testCheckLoadedUserProperties($property, $value, $user)
    {
        $this->assertEquals(
            $value,
            $user->$property
        );
    }

    /**
     * @depends testStoreAndRetriveUser
     */
    public function testFindAllUsers($user)
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');

        $this->assertEquals(
            array($user),
            $userService->findUsers()
        );
    }

    /**
     * @depends testStoreAndRetriveUser
     */
    public function testReloadUser($user)
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');

        $reloadedUser = $userService->reloadUser($user);

        $this->assertEquals($user, $reloadedUser);
    }

    /**
     * @depends testStoreAndRetriveUser
     */
    public function testUpdatePlainPassword($user)
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');
        $user->setPlainPassword("password");

        $userService->updatePassword($user);

        $this->assertNotNull($user->auth->password);
        return $user;
    }

    /**
     * @depends testUpdatePlainPassword
     */
    public function testUpdateAndClearPlainPassword($user)
    {
        $this->assertNull($user->getPlainPassword());
    }

    /**
     * @depends testStoreAndRetriveUser
     */
    public function testFindUserByEmail($user)
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');

        $loadedUser = $userService->findUserByEmail("kore@example.com");

        $this->assertEquals($user, $loadedUser);
    }

    /**
     * @depends testStoreAndRetriveUser
     */
    public function testFindUserByConfirmationToken($user)
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');

        $user->auth->confirmationToken = 'token';
        $userService->updateUser($user);

        $loadedUser = $userService->findUserByConfirmationToken("token");

        $this->assertEquals($user, $loadedUser);
    }

    /**
     * @return array[]
     */
    public function getUsernameAndEmail()
    {
        return array(
            array('kore'),
            array('kore@example.com'),
        );
    }

    /**
     * @dataProvider getUsernameAndEmail
     * @depends testStoreAndRetriveUser
     */
    public function testFindUserByUsernameOrEmail($value, $user)
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');

        $loadedUser = $userService->findUserByUsernameOrEmail($value);

        $this->assertEquals($user, $loadedUser);
    }

    /**
     * @IMORTANT Should be the last test, obviously.
     *
     * @depends testStoreAndRetriveUser
     */
    public function testDeleteUser($user)
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');

        $userService->deleteUser($user);

        $this->assertEquals(
            array(),
            $userService->findUsers()
        );
    }
}

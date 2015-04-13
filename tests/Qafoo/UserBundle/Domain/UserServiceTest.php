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
}

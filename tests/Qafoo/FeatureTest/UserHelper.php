<?php

namespace Qafoo\FeatureTest;

use Qafoo\UserBundle\Domain\Name;
use Qafoo\UserBundle\Domain\Email;

trait UserHelper
{
    /**
     * Create user
     *
     * @param string $login
     * @param string $password
     * @param string $name = null
     * @return User
     */
    protected function createUser($login, $password, $email, $name = null)
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');
        $user = $userService->createUser();
        $user->login = $login;
        $user->email = new Email($email);
        $user->name = new Name($name);
        $user->setPlainPassword($password);

        $userService->updateUser($user);
        return $user;
    }
}

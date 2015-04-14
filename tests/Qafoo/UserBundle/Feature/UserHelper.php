<?php

namespace Qafoo\Feature;

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
    protected function createUser($login, $password, $name = null)
    {
        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');
        $user = $userService->createUser();
        $user->login = $login;
        $user->setPlainPassword($password);
        $user->name = new Name($name);

        $userService->updatePassword($user);
        return $user;
    }
}

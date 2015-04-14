<?php

namespace Qafoo\FeatureTest;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

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

        try {
            return $userService->getUserByLogin($login);
        } catch (UsernameNotFoundException $e) {
            // Ignore and just create user
        }

        $user = $userService->createUser();
        $user->login = $login;
        $user->email = new Email($email);
        $user->name = new Name($name);
        $user->setPlainPassword($password);

        $userService->updateUser($user);
        return $user;
    }

    /**
     * Login user
     *
     * @param string $login
     * @return User
     */
    protected function loginUser($login, $password = 'password')
    {
        $page = $this->visit('/auth');
        $page->find('css', '#username')->setValue($login);
        $page->find('css', '#password')->setValue($password);
        $page->find('css', '#submit')->press();

        $page = $this->session->getPage();
        $this->assertNotNull(
            $welcomeBox = $page->find('css', '.alert-info'),
            'Login failed'
        );
    }
}

<?php

namespace Qafoo\FeatureTest;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Qafoo\UserBundle\Domain\Name;
use Qafoo\UserBundle\Domain\EMail;

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
        $user->email = new EMail($email);
        $user->name = Name::createFromName($name);
        $user->setPlainPassword($password);

        $userService->updateUser($user);
        return $user;
    }

    /**
     * getUser
     *
     * @param string $login
     * @return User
     */
    protected function getUser($login)
    {
        $documentManager = $this->getContainer()->get('doctrine_couchdb.odm.document_manager');
        $documentManager->clear();

        $userService = $this->getContainer()->get('qafoo.user.domain.user_service');
        return $userService->getUserByLogin($login);
    }

    /**
     * Login user
     *
     * @param string $login
     * @return void
     */
    protected function loginUser($login, $password = 'password')
    {
        $page = $this->visit('/login');
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

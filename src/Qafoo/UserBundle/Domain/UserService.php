<?php

namespace Qafoo\UserBundle\Domain;

use Qafoo\UserBundle\Gateway\UserGateway;

class UserService
{
    /**
     * User gateway
     *
     * @var UserGateway
     */
    private $userGateway;

    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    /**
     * Get all users
     *
     * @return User[]
     */
    public function getAllUsers()
    {
        return $this->userGateway->getAllUsers();
    }

    /**
     * Get user by login
     *
     * @param string $login
     * @return User
     */
    public function getUserByLogin($login)
    {
        return $this->userGateway->loadUserByUsername($login);
    }
}

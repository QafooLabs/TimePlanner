<?php

namespace Qafoo\UserBundle\Gateway\UserGateway;

use Qafoo\UserBundle\Gateway\UserGateway;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;

use Qafoo\UserBundle\Domain\User;
use Qafoo\UserBundle\Domain\FOSUser;

class MySQL extends UserGateway
{
    /**
     * Entity manager
     *
     * @var EntityRepository
     */
    private $entityRepository;

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
    }

    /**
     * Store
     *
     * @param User $user
     * @return User
     */
    public function store(User $user)
    {
    }

    /**
     * Remove
     *
     * @param User $user
     * @return void
     */
    public function remove(User $user)
    {
    }

    /**
     * Get all users
     *
     * @return User[]
     */
    public function getAllUsers()
    {
    }

    /**
     * Find by property
     *
     * @param string $property
     * @param mixed $value
     * @return User
     */
    public function findByProperty($property, $value)
    {
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
    }
}

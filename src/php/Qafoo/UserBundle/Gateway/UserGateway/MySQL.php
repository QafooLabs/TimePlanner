<?php

namespace Qafoo\UserBundle\Gateway\UserGateway;

use Qafoo\UserBundle\Gateway\UserGateway;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Qafoo\UserBundle\Domain\User;
use Qafoo\UserBundle\Domain\FOSUser;

class MySQL extends UserGateway
{
    /**
     * Entity repository
     *
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * Entity manager
     *
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityRepository $entityRepository, EntityManager $entityManager)
    {
        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
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
        if (($user = $this->entityRepository->find($username)) === null) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    /**
     * Store
     *
     * @param User $user
     * @return User
     */
    public function store(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
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

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

    /**
     * Property map
     *
     * @var array
     */
    private $propertyMap = array(
        'email' => 'email.email',
        'token' => 'auth.confirmationToken',
    );

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
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * Get all users
     *
     * @return User[]
     */
    public function getAllUsers()
    {
        return $this->entityRepository->findAll();
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
        if (!isset($this->propertyMap[$property])) {
            throw new \OutOfBoundsException("Unknown property $property to filter by.");
        }

        $query = $this->entityManager->createQuery(
            "SELECT u
                FROM Qafoo\UserBundle\Domain\FOSUser u
                WHERE u.{$this->propertyMap[$property]} = :value"
        );
        $query->setParameter('value', $value);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new \OutOfBoundsException("No user found with $property $value", 0, $e);
        }
    }
}

<?php

namespace Qafoo\UserBundle\Gateway;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ODM\CouchDB\DocumentRepository;

use Qafoo\UserBundle\Domain\User;

class UserGateway implements UserProviderInterface
{
    const USER_CLASS = 'Qafoo\\UserBundle\\Domain\\FOSUserHelper';

    /**
     * Document manager
     *
     * @var DocumentRepository
     */
    private $documentRepository;

    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;

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
        $user = $this->documentRepository->find($username);

        if (!$user) {
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
        $documentManager = $this->documentRepository->getDocumentManager();
        $documentManager->persist($user);
        $documentManager->flush();

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
        $documentManager = $this->documentRepository->getDocumentManager();
        $documentManager->remove($user);
        $documentManager->flush();
    }

    /**
     * Get all users
     *
     * @return User[]
     */
    public function getAllUsers()
    {
        return $this->documentRepository->findAll();
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
        $query = $this->documentRepository->getDocumentManager()->createQuery('users', 'index');
        $result = $query
            ->setKey(array($property, $value))
            ->setIncludeDocs(true)
            ->setReduce(false)
            ->onlyDocs(true)
            ->execute();

        if (!count($result) === 1) {
            throw new \OutOfBoundsException("No user found with $property $value");
        }

        return $result->toArray()[0];
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
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(
                sprintf(
                    'Expected an instance of %s, but got "%s".',
                    self::USER_CLASS,
                    get_class($user)
                )
            );
        }

        if (null === $reloadedUser = $this->loadUserByUsername($user->getUsername())) {
            throw new UsernameNotFoundException(
                sprintf(
                    'User with login "%d" could not be reloaded.',
                    $user->getUsername()
                )
            );
        }

        return $reloadedUser;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return self::USER_CLASS === $class ||
            is_subclass_of($class, self::USER_CLASS);
    }
}

<?php

namespace Qafoo\UserBundle\Gateway\UserGateway;

use Qafoo\UserBundle\Gateway\UserGateway;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ODM\CouchDB\DocumentRepository;

use Qafoo\UserBundle\Domain\User;
use Qafoo\UserBundle\Domain\FOSUser;

class CouchDB extends UserGateway
{
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

        $documents = $result->toArray();
        if (count($documents) !== 1) {
            throw new \OutOfBoundsException("No user found with $property $value");
        }

        return $documents[0];
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
                    FOSUser::class,
                    get_class($user)
                )
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }
}

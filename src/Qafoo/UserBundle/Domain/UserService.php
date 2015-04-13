<?php

namespace Qafoo\UserBundle\Domain;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;

use Qafoo\UserBundle\Gateway\UserGateway;

class UserService implements UserManagerInterface
{
    /**
     * Password encoder factory
     *
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * User gateway
     *
     * @var UserGateway
     */
    private $userGateway;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(UserGateway $userGateway, EncoderFactoryInterface $encoderFactory)
    {
        $this->userGateway = $userGateway;
        $this->encoderFactory = $encoderFactory;
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

    /**
     * Creates an empty user instance.
     *
     * @return UserInterface
     */
    public function createUser()
    {
        return new FOSUserHelper();
    }

    /**
     * Deletes a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function deleteUser(UserInterface $user)
    {
        $this->userGateway->remove($user);
    }

    /**
     * Finds one user by the given criteria.
     *
     * @param array $criteria
     *
     * @return UserInterface
     */
    public function findUserBy(array $criteria)
    {
        throw \BadMethodCallException("Not implemented, underspecified method.");
    }

    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByUsername($username)
    {
        try {
            return $this->getUserByLogin($username);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Finds a user by its email.
     *
     * @param string $email
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByEmail($email)
    {
        // @TODO
        return;
    }

    /**
     * Finds a user by its username or email.
     *
     * @param string $usernameOrEmail
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * Finds a user by its confirmationToken.
     *
     * @param string $token
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByConfirmationToken($token)
    {
        // @TODO
        return;
    }

    /**
     * Returns a collection with all user instances.
     *
     * @return UserInterface[]
     */
    public function findUsers()
    {
        return $this->userGateway->getAllUsers();
    }

    /**
     * Returns the user's fully qualified class name.
     *
     * @return string
     */
    public function getClass()
    {
        return FOSUserHelper::CLASS;
    }

    /**
     * Reloads a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function reloadUser(UserInterface $user)
    {
        return $this->getUserByLogin($user->login);
    }

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updateUser(UserInterface $user)
    {
        $this->userGateway->store($user);
    }

    /**
     * Updates the canonical username and email fields for a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        // There is nothing to do in our case
    }

    /**
     * Updates a user password if a plain password is set.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updatePassword(UserInterface $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    protected function getEncoder(UserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }
}

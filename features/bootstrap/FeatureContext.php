<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Qafoo\UserBundle\Domain\Name;
use Qafoo\UserBundle\Domain\EMail;
use Qafoo\IntegrationTest;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given The user :arg1 with password :arg2 exists
     */
    public function theUserWithPasswordExists($login, $password)
    {
        $container = IntegrationTest::getContainer();
        $userService = $container->get('qafoo.user.domain.user_service');

        try {
            return $userService->getUserByLogin($login);
        } catch (UsernameNotFoundException $e) {
            // Ignore and just create user
        }

        $user = $userService->createUser();
        $user->login = $login;
        $user->email = new EMail($login . '@example.com');
        $user->name = Name::createFromName($login);
        $user->setPlainPassword($password);

        $userService->updateUser($user);
        return $user;
    }

}

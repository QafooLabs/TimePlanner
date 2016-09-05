<?php

namespace Qafoo\UserBundle\Feature\PageObject;

use Qafoo\FeatureTest\UserHelper;
use Qafoo\FeatureTest;
use Qafoo\Page;

/**
 * @group feature
 */
class LoginTest extends FeatureTest
{
    use UserHelper;

    public function setUp()
    {
        $this->start();
    }

    public function testLogInWithWrongPassword()
    {
        $page = (new Page\Login($this->session))->visit(Page\Login::PATH);

        $page->setUser('kore');
        $page->setPassword('wrongPassword');
        $newPage = $page->login();

        $this->assertInstanceOf(Page\Login::class, $newPage);
    }

    public function testSuccessfulLogIn()
    {
        $page = (new Page\Login($this->session))->visit(Page\Login::PATH);

        $this->createUser('kore', 'password', 'kore@example.com');

        $page->setUser('kore');
        $page->setPassword('password');
        $newPage = $page->login();

        $this->assertInstanceOf(Page\Overview::class, $newPage);
    }
}

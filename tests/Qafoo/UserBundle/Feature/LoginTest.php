<?php

namespace Qafoo\Feature;

use Qafoo\FeatureTest;

/**
 * @group feature
 */
class LoginTest extends FeatureTest
{
    use FeatureTest\UserHelper;

    public function testLoginRedirect()
    {
        $page = $this->visit('/');
        $title = $page->find('css', 'h2');

        $this->assertEquals("Login", $title->getText());
    }

    public function testLogin()
    {
        $user = $this->createUser('kore', 'password', 'kore@example.com', 'Kore Nordmann');

        $page = $this->visit('/');
        $page->find('css', '#username')->setValue('kore');
        $page->find('css', '#password')->setValue('password');
        $page->find('css', '#submit')->press();

        $page = $this->session->getPage();
        $this->assertNotNull(
            $welcomeBox = $page->find('css', '.alert-info'),
            'Login failed'
        );
        $this->assertContains("Hello Kore", $welcomeBox->getText());
    }

    /**
     * @depends testLogin
     */
    public function testForgotPassword()
    {
        $page = $this->visit('/request');

        $page->find('css', '#username')->setValue('kore');
        $page->find('css', 'form button')->press();

        // @Hack Sahi tends to return before the user is actually updated
        usleep(500 * 1000);
        $user = $this->getUser('kore');
        $this->assertNotNull($user->auth->confirmationToken, "Confirmation token not set");

        $page = $this->visit('/reset/' . $user->auth->confirmationToken);
        $page->find('css', '#fos_user_resetting_form_new_first')->setValue('new');
        $page->find('css', '#fos_user_resetting_form_new_second')->setValue('new');
        $page->find('css', 'form button')->press();

        $page = $this->visit('/logout');
        $this->loginUser('kore', 'new');
    }
}

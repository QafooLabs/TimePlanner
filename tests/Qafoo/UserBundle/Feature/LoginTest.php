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
        $this->createUser('kore', 'password', 'kore@example.com', 'Kore Nordmann');

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
}

<?php

namespace Qafoo\Feature;

use Qafoo\FeatureTest;

/**
 * @group feature
 */
class ProfileTest extends FeatureTest
{
    use FeatureTest\UserHelper;

    public function testViewProfile()
    {
        $user = $this->createUser('kore', 'password', 'kore@example.com', 'Kore');
        $this->loginUser('kore', 'password');

        $page = $this->visit('/account');
        $this->assertNotNull(
            $page->find('css', '.container'),
            "Profile page not working."
        );

        $values = array(
            '.test-name' => 'Kore',
            '.test-login' => 'kore',
            '.test-email' => 'kore@example.com',
        );

        foreach ($values as $class => $value) {
            $this->assertNotNull(
                $testElement = $page->find('css', $class),
                "Test element with selector $class not found"
            );
            $this->assertContains($value, $testElement->getText());
        }
    }

    public function testChangeName()
    {
        $user = $this->createUser('kore', 'password', 'kore@example.com', 'Kore');
        $this->loginUser('kore', 'password');

        $page = $this->visit('/account/edit');
        $page->find('css', '#fos_user_profile_form_name')->setValue('Kore Nordmann');
        $page->find('css', '#fos_user_profile_form_current_password')->setValue('password');
        $page->find('css', 'form button')->press();

        $this->assertNotNull(
            $testElement = $page->find('css', '.test-name'),
            "Profile page not correctly loaded after edit"
        );
        $this->assertContains("Kore Nordmann", $testElement->getText());
    }

    public function testUpdatePassword()
    {
        $user = $this->loginUser('kore');

        $page = $this->visit('/change-password');
        $page->find('css', '#fos_user_change_password_form_current_password')->setValue('password');
        $page->find('css', '#fos_user_change_password_form_new_first')->setValue('updated');
        $page->find('css', '#fos_user_change_password_form_new_second')->setValue('updated');
        $page->find('css', 'form button')->press();

        $this->assertNotNull(
            $testElement = $page->find('css', '.test-name'),
            "Profile page not correctly loaded after edit"
        );

        $this->visit('/logout');
        $this->loginUser('kore', 'updated');
    }
}

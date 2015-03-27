<?php

namespace Qafoo\UserBundle\Domain\User;

/**
 * @covers Qafoo\UserBundle\Domain\User\Authentification
 */
class AuthentificationTest extends \PHPUnit_Framework_TestCase
{
    public function testSaltNotEmpty()
    {
        $auth = new Authentification();
        $this->assertNotEmpty($auth->salt);
    }

    public function testSetSalt()
    {
        $auth = new Authentification(array(
            'salt' => $expectation = 'salt',
        ));

        $this->assertSame($expectation, $auth->salt);
    }
}

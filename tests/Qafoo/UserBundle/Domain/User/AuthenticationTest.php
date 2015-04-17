<?php

namespace Qafoo\UserBundle\Domain\User;

/**
 * @covers Qafoo\UserBundle\Domain\User\Authentication
 */
class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    public function testSaltNotEmpty()
    {
        $auth = new Authentication();
        $this->assertNotEmpty($auth->salt);
    }

    public function testSetSalt()
    {
        $auth = new Authentication(array(
            'salt' => $expectation = 'salt',
        ));

        $this->assertSame($expectation, $auth->salt);
    }
}

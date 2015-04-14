<?php

namespace Qafoo;

use Behat\Mink\Driver;
use Behat\Mink\Session;

abstract class FeatureTest extends IntegrationTest
{
    protected $session;

    public function setUp()
    {
        switch (strtolower(getenv('DRIVER'))) {
            case 'sahi':
                $browser = getenv('BROWSER') ?: 'firefox';
                $driver = new Driver\SahiDriver($browser);
                break;

            case 'goutte':
            default:
                $driver = new Driver\GoutteDriver();
        }

        $this->session = new Session($driver);
        $this->session->start();
    }

    public function tearDown()
    {
        if ($this->session) {
            $this->session->stop();
        }
    }

    /**
     * Visit given path
     *
     * @param string $path
     * @return \Behat\Mink\Element\DocumentElement
     */
    protected function visit($path)
    {
        $domain = getenv('DOMAIN') ?: 'http://localhost:8888';
        $this->session->visit($domain . $path);
        return $this->session->getPage();
    }
}

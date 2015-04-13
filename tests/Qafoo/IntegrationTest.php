<?php

namespace Qafoo;

abstract class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    protected function getContainer()
    {
        if (!$this->container) {
            $this->kernel = new TestKernel('test', true);
            $this->kernel->boot();
            $this->container = $this->kernel->getContainer();
        }

        return $this->container;
    }
}

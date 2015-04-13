<?php

namespace Qafoo;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

require __DIR__ . '/../../app/AppKernel.php';

class TestKernel extends \AppKernel
{
    public static function getAdditionalConfigFiles()
    {
        return array_merge(
            parent::getAdditionalConfigFiles(),
            array('build.properties.test')
        );
    }
}

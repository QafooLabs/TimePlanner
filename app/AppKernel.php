<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),

            new FOS\UserBundle\FOSUserBundle(),

            new Qafoo\UserBundle\QafooUserBundle(),
            new Qafoo\TimePlannerBundle\QafooTimePlannerBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $loader->load(function ($container) {
                $container->loadFromExtension('web_profiler', array(
                    'toolbar' => true,
                ));
            });
        }
    }

    /**
     * Get environment
     *
     * @return string
     */
    public static function getEnvironmentFromConfiguration()
    {
        $environemntVariables = array(
            'env' => 'prod',
        );

        $baseDir = __DIR__ . '/../';
        foreach (array('build.properties', 'build.properties.local') as $file) {
            if (file_exists($fileName = $baseDir . $file)) {
                $environemntVariables = array_merge($environemntVariables, parse_ini_file($fileName));
            }
        }

        return $environemntVariables['env'];
    }

    /**
     * Get debug
     *
     * @return bool
     */
    public static function getDebug()
    {
        return in_array(self::getEnvironmentFromConfiguration(), array('dev', 'test'));
    }
}

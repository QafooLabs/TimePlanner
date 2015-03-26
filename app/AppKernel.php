<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * Configuration
     *
     * @var array
     */
    private static $configuration;

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\CouchDBBundle\DoctrineCouchDBBundle(),
            new QafooLabs\Bundle\NoFrameworkBundle\QafooLabsNoFrameworkBundle(),

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
     * Builds the service container.
     *
     * @return ContainerBuilder The compiled service container
     *
     * @throws \RuntimeException
     */
    protected function buildContainer()
    {
        $container = parent::buildContainer();

        foreach (self::getConfiguration() as $key => $value) {
            $container->setParameter($key, $value);
        }

        return $container;
    }

    /**
     * Initialize configuration
     *
     * @return void
     */
    public static function getConfiguration()
    {
        if (self::$configuration) {
            return self::$configuration;
        }

        self::$configuration = array(
            'env' => 'prod',
            'debug' => false,
        );

        $baseDir = __DIR__ . '/../';
        foreach (array('build.properties', 'build.properties.local') as $file) {
            if (file_exists($fileName = $baseDir . $file)) {
                self::$configuration = array_merge(self::$configuration, parse_ini_file($fileName));
            }
        }

        return self::$configuration;
    }

    /**
     * Get environment
     *
     * @return string
     */
    public static function getEnvironmentFromConfiguration()
    {
        return self::getConfiguration()['env'];
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

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

    private static $databaseTypes = array(
        'couchdb' => array(
            'database.driver' => 'pdo_sqlite',
            'database.mapping' => 'couchdb',
        ),
        'mysql' => array(
            'database.driver' => 'pdo_mysql',
            'database.mapping' => 'orm',
        ),
        'sqlite' => array(
            'database.driver' => 'pdo_sqlite',
            'database.mapping' => 'orm',
        ),
    );

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\CouchDBBundle\DoctrineCouchDBBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
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
                    'intercept_redirects' => false,
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
            'monolog_action_level' => 'error',
        );

        foreach (static::getAdditionalConfigFiles() as $file) {
            if (file_exists($file)) {
                self::$configuration = array_merge(self::$configuration, parse_ini_file($file));
            }
        }

        self::$configuration = array_merge(
            self::$configuration,
            self::$databaseTypes[self::$configuration['database.type']]
        );

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
     * Get additional config files
     *
     * @return string[]
     */
    public static function getAdditionalConfigFiles()
    {
        $files = array(
            __DIR__ . '/../environment',
            __DIR__ . '/../environment.local',
        );

        if (getenv("CONFIG")) {
            $files[] = getenv("CONFIG");
        }
        return $files;
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

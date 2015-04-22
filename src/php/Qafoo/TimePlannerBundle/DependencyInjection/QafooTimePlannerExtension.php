<?php

namespace Qafoo\TimePlannerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 */
class QafooTimePlannerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $databaseType = $container->getParameter('database.type');
        $container->setAlias(
            'qafoo.time_planner.gateway.vacation',
            'qafoo.time_planner.gateway.vacation.' . $databaseType
        );
        $container->setAlias(
            'qafoo.time_planner.gateway.job',
            'qafoo.time_planner.gateway.job.' . $databaseType
        );
        $container->setAlias(
            'qafoo.time_planner.gateway.public_holiday',
            'qafoo.time_planner.gateway.public_holiday.' . $databaseType
        );
        $container->setAlias(
            'qafoo.time_planner.gateway.meta_data',
            'qafoo.time_planner.gateway.meta_data.' . $databaseType
        );
    }
}

<?php

namespace Qafoo;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\SchemaTool;

require __DIR__ . '/../../app/AppKernel.php';

abstract class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    private static $container;

    protected static function getContainer()
    {
        if (!self::$container) {
            $kernel = new \AppKernel('test', true);
            $kernel->boot();
            self::$container = $kernel->getContainer();
        }

        return self::$container;
    }

    public static function setUpBeforeClass()
    {
        self::$container = null;
        $databaseType = self::getContainer()->getParameter('database.type');
        $method = 'initialize' . ucfirst($databaseType);
        self::$method(self::getContainer());
    }

    protected static function initializeCouchdb($container)
    {
        $couchDbConnection = self::getContainer()->get('doctrine_couchdb.client.default_connection');

        try {
            $couchDbConnection->getHttpClient()->request('DELETE', '/' . $couchDbConnection->getDatabase());
        } catch (\Doctrine\CouchDB\HTTP\HTTPException $e) {
            // Just ignore if database did not exist
        }

        $couchDbConnection->getHttpClient()->request('PUT', '/' . $couchDbConnection->getDatabase());
    }

    protected static function initializeMysql($container)
    {
        $connection = self::getContainer()->get('doctrine.dbal.default_connection');
        $parameters = $connection->getParams();
        unset($parameters['dbname']);

        $tmpConnection = DriverManager::getConnection($parameters);
        $schemaManager = $tmpConnection->getSchemaManager();
        $schemaManager->dropAndCreateDatabase($container->getParameter('database.name'));

        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $entityMetaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->createSchema($entityMetaData);
    }

    public function setUp()
    {
        parent::setUp();

        $documentManager = $this->getContainer()->get('doctrine_couchdb.odm.document_manager');
        $documentManager->clear();

        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->clear();
    }
}

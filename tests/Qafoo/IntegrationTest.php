<?php

namespace Qafoo;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\SchemaTool;

require __DIR__ . '/../../app/AppKernel.php';

abstract class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    private static $container;

    private static $databaseFailure = false;

    public static function getContainer()
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
        self::$databaseFailure = false;

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

        try {
            $couchDbConnection->getHttpClient()->request('PUT', '/' . $couchDbConnection->getDatabase());
        } catch (\Doctrine\CouchDB\HTTP\HTTPException $e) {
            self::$databaseFailure = "Could not connect to CouchDB server.";
        }
    }

    protected static function initializeMysql($container)
    {
        if (!extension_loaded('pdo_mysql')) {
            self::$databaseFailure = "Required extension pdo_sqlite missing";
        }

        $connection = self::getContainer()->get('doctrine.dbal.default_connection');
        $parameters = $connection->getParams();
        unset($parameters['dbname']);

        try {
            $tmpConnection = DriverManager::getConnection($parameters);
            $schemaManager = $tmpConnection->getSchemaManager();
            $schemaManager->dropAndCreateDatabase($container->getParameter('database.name'));
        } catch (\Exception $e) {
            self::$databaseFailure = "Could not connect to MySQL server: " . $e->getMessage();
            return;
        }

        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $entityMetaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->createSchema($entityMetaData);
    }

    protected static function initializeSqlite($container)
    {
        if (!extension_loaded('pdo_sqlite')) {
            self::$databaseFailure = "Required extension pdo_sqlite missing";
        }

        $connection = self::getContainer()->get('doctrine.dbal.default_connection');
        $parameters = $connection->getParams();
        unset($parameters['path']);

        $tmpConnection = DriverManager::getConnection($parameters);
        $schemaManager = $tmpConnection->getSchemaManager();
        $schemaManager->dropAndCreateDatabase($container->getParameter('database.path'));

        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $entityMetaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->createSchema($entityMetaData);
    }

    public function setUp()
    {
        parent::setUp();

        if (self::$databaseFailure) {
            $this->markTestSkipped('Integration test skipped: ' . self::$databaseFailure);
        }

        $documentManager = $this->getContainer()->get('doctrine_couchdb.odm.document_manager');
        $documentManager->clear();

        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->clear();
    }
}

<?php

namespace Qafoo;

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
        $couchDbConnection = self::getContainer()->get('doctrine_couchdb.client.default_connection');

        try {
            $couchDbConnection->getHttpClient()->request('DELETE', '/' . $couchDbConnection->getDatabase());
        } catch (\Doctrine\CouchDB\HTTP\HTTPException $e) {
            // Just ignore if database did not exist
        }

        $couchDbConnection->getHttpClient()->request('PUT', '/' . $couchDbConnection->getDatabase());
    }

    public function setUp()
    {
        parent::setUp();

        $documentManager = $this->getContainer()->get('doctrine_couchdb.odm.document_manager');
        $documentManager->clear();
    }
}

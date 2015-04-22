<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Doctrine\ODM\CouchDB\DocumentRepository;
use Doctrine\CouchDB\CouchDBClient;

use Qafoo\TimePlannerBundle\Domain\MetaData;
use Qafoo\TimePlannerBundle\Domain\DaySet;
use Qafoo\TimePlannerBundle\Domain\Day;

abstract class MetaDataGateway
{
    /**
     * Get last edits
     *
     * @param string $type
     * @param int $count
     * @return object[]
     */
    abstract public function getLastEdits($type, $count = 10);
}

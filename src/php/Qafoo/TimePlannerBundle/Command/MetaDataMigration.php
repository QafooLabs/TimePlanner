<?php

namespace Qafoo\TimePlannerBundle\Command;

use Doctrine\CouchDB\Tools\Migrations\AbstractMigration;

class MetaDataMigration extends AbstractMigration
{
    protected function migrate(array $docData)
    {
        if (isset($docData['metaData']) &&
            isset($docData['metaData']['author']) &&
            isset($docData['metaData']['author']['_id'])) {
            $docData['metaData']['author'] = $docData['metaData']['author']['_id'];
            return $docData;
        }
    }
}

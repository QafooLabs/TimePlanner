<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Qafoo\UserBundle\Domain\User;
use Qafoo\TimePlannerBundle\Gateway\MetaDataGateway;

class MetaDataService
{
    /**
     * MetaData gateway
     *
     * @var MetaDataGateway
     */
    private $metaDataGateway;

    public function __construct(MetaDataGateway $metaDataGateway)
    {
        $this->metaDataGateway = $metaDataGateway;
    }

    /**
     * Get last edits
     *
     * @param string $type
     * @param int $count
     * @return object[]
     */
    public function getLastEdits($type, $count = 10)
    {
        return $this->metaDataGateway->getLastEdits($type, $count);
    }
}

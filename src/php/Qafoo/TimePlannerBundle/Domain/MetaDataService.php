<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Qafoo\UserBundle\Domain\UserService;
use Qafoo\TimePlannerBundle\Gateway\MetaDataGateway;

class MetaDataService
{
    /**
     * MetaData gateway
     *
     * @var MetaDataGateway
     */
    private $metaDataGateway;

    /**
     * User service
     *
     * @var UserService
     */
    private $userService;

    public function __construct(MetaDataGateway $metaDataGateway, UserService $userService)
    {
        $this->metaDataGateway = $metaDataGateway;
        $this->userService = $userService;
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
        $objects = $this->metaDataGateway->getLastEdits($type, $count);
        foreach ($objects as $object) {
            if ($object->metaData instanceof MetaData &&
                $object->metaData->author) {
                $object->metaData->author = $this->userService->getUserByLogin($object->metaData->author);
            }
        }

        return $objects;
    }
}

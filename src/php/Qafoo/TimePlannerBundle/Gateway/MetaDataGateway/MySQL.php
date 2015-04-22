<?php

namespace Qafoo\TimePlannerBundle\Gateway\MetaDataGateway;

use Qafoo\TimePlannerBundle\Gateway\MetaDataGateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\Mapping\MappingException;

use Qafoo\TimePlannerBundle\Domain\MetaData;
use Qafoo\TimePlannerBundle\Domain\DaySet;
use Qafoo\TimePlannerBundle\Domain\Day;

class MySQL extends MetaDataGateway
{
    /**
     * Entity repository
     *
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * Entity manager
     *
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityRepository $entityRepository, EntityManager $entityManager)
    {
        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
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
        try {
            $this->entityManager->getClassMetadata($type);
        } catch (MappingException $e) {
            return array();
        }

        $query = $this->entityManager->createQuery(
            "SELECT o
                FROM $type o
                ORDER BY o.metaData.changed DESC"
        );
        $query->setMaxResults($count);

        return $query->getResult();
    }
}

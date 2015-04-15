<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Doctrine\ODM\CouchDB\DocumentRepository;
use Doctrine\CouchDB\CouchDBClient;

use Qafoo\TimePlannerBundle\Domain\MetaData;
use Qafoo\TimePlannerBundle\Domain\DaySet;
use Qafoo\TimePlannerBundle\Domain\Day;

class MetaDataGateway
{
    /**
     * Document repository
     *
     * @var DocumentRepository
     */
    private $documentRepository;

    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
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
        $query = $this->documentRepository->getDocumentManager()->createQuery('meta_data', 'index');
        $type = str_replace('\\', '.', $type);
        $result = $query
            ->setStartKey(array($type, CouchDBClient::COLLATION_END))
            ->setEndKey(array($type))
            ->setDescending(true)
            ->setIncludeDocs(true)
            ->setReduce(false)
            ->setLimit($count)
            ->onlyDocs(true)
            ->execute();

        return $result->toArray();
    }
}

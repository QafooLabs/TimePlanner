<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Doctrine\ODM\CouchDB\DocumentRepository;
use Doctrine\CouchDB\CouchDBClient;

use Qafoo\TimePlannerBundle\Domain\PublicHoliday;

class PublicHolidayGateway
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
     * Store
     *
     * @param PublicHoliday $publicHoliday
     * @return PublicHoliday
     */
    public function store(PublicHoliday $publicHoliday)
    {
        $documentManager = $this->documentRepository->getDocumentManager();
        $documentManager->persist($publicHoliday);
        $documentManager->flush();

        return $publicHoliday;
    }
}

<?php

namespace Qafoo\TimePlannerBundle\Gateway;

use Doctrine\ODM\CouchDB\DocumentRepository;

class Vacation
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
     * Get next vacations
     *
     * @param int $count
     * @return Vacation[]
     */
    public function getNextVacations($count = 10)
    {
        $query = $this->documentRepository->getDocumentManager()->createQuery('vacation', 'upcoming');
        $result = $query
            ->setStartKey(date('Y-m-d'))
            ->setLimit($count)
            ->onlyDocs(true)
            ->execute();

        return $result->toArray();
    }

    /**
     * Store
     *
     * @param Vacation $vacation
     * @return Vacation
     */
    public function store(Vacation $vacation)
    {
        $documentManager = $this->documentRepository->getDocumentManager();
        $documentManager->persist($vacation);
        $documentManager->flush();

        return $vacation;
    }
}

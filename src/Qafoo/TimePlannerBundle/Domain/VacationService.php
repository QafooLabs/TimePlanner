<?php

namespace Qafoo\TimePlannerBundle\Domain;

use Doctrine\ODM\CouchDB\DocumentRepository;

use Qafoo\UserBundle\Domain\User;

class VacationService
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
     * Get remaining vacation days
     *
     * @param User $user
     * @return int
     */
    public function getRemainingVacationDays(User $user)
    {
        return 12;
    }

    /**
     * Get next vacations
     *
     * @param int $count
     * @return Vacation[]
     */
    public function getNextVacations($count = 10)
    {
        return array();
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

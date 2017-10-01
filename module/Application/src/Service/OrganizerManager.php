<?php

namespace Application\Service;

use Application\Entity\Organizer;

class OrganizerManager {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getList() {
        $organizers = $this->entityManager->getRepository(Organizer::class)->findAll();
        return $organizers;
    }
}

<?php

namespace Application\Service;

use Application\Entity\Concert;

class ConcertManager {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var \Application\Service\UserManager
     */
    private $userManager;

    public function __construct($entityManager, $authenticationService, $userManager) {
        $this->entityManager = $entityManager;
        $this->authenticationService = $authenticationService;
        $this->userManager = $userManager;
    }

    public function getList() {
        $concerts = $this->entityManager->getRepository(Concert::class)->findAll();
        return $concerts;
    }

    /**
     * @param $concert
     * @param $data
     * @return Concert
     */
    public function editConcert($concert, $data) {
        $isNew = false;
        if ($concert === null) {
            $concert = new Concert();
            $isNew = true;
        }

        $concert->setArtist($data['artist']);
        $concert->setDate(new \DateTime($data['date']));
        $concert->setPrice($data['price']);
        $concert->setLocation($data['location']);
        $concert->setAvailability($data['availability']);
        $organizer = $this->userManager->getCurrentUser();
        $concert->setOrganizer($organizer);

        if ($isNew) {
            $this->entityManager->persist($concert);
        }

        $this->entityManager->flush();

        return $concert;
    }

    /**
     * 
     * @param type $id
     * @return boolean
     */
    public function deleteConcert($id) {
        $concert_removed = $this->entityManager->getRepository(Concert::class)->find($id);

        echo "\n id passato = " . $id;

        if ($id === null | $id < 0) {
            echo "concerto non trovato" . $id;
            return false;
        }
        
          $this->entityManager->remove($concert_removed);
          $this->entityManager->flush();

          echo "concerto trovato" . $id;

          return true;
        
    }

}

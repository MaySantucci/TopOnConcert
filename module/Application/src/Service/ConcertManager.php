<?php

namespace Application\Service;

use Application\Entity\Concert;

class ConcertManager {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
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
        $organizer = $this->entityManager->getRepository(\Application\Entity\Organizer::class)->find(3);
        $concert->setOrganizer($organizer);
        //echo \get_class($organizer);

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

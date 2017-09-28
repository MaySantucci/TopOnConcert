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
        $concert->setDate($data['date']);
        $concert->setPrice($data['price']);
        $concert->setLocation($data['location']);
        $concert->setAvailability($data['availability']);
        $concert->setOrganizer($data['organizer']);


        if ($isNew) {
            $this->entityManager->persist($concert);
        }
        $this->entityManager->flush();

        return $concert;
    }

    public function deleteConcert($id) {

        if ($id === null) {
            return false;
        } elseif ($id >= 0) {
            $concert = $this->entityManager->getRepository(Concert::class)->find($id);
            $this->entityManager->remove($concert);
            return true;
        }
    }

}

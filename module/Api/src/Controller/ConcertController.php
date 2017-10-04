<?php

namespace Api\Controller;

use Application\Entity\Concert;
use Zend\View\Model\JsonModel;

class ConcertController extends AbstractController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Application\Service\ConcertManager
     */
    private $concertManager;

    /**
     * @var \Application\Service\UserManager
     */
    private $userManager;

    public function __construct($entityManager, $concertManager, $userManager)
    {
        $this->entityManager = $entityManager;
        $this->concertManager = $concertManager;
        $this->userManager = $userManager;
    }

    public function get($id)
    {
        $concert = $this->entityManager->getRepository(Concert::class)->find($id);

        $concert = [
            'id' => $concert->getId(),
            'artist' => $concert->getArtist(),
            'location' => $concert->getLocation(),
            'date' => date_format($concert->getDate(), 'd-m-Y'),
            'price' => $concert->getPrice() . ' €',
            'availability' => $concert->getAvailability(),
            'organizer' => $concert->getOrganizer()->getFullName(),
        ];

        $result = [
            "concert" => $concert
        ];

        return new JsonModel($result);

    }

    public function getList()
    {
        $concerts = [];

        /** @var Concert $concert */
        foreach ($this->concertManager->getList() as $concert) {
            $concerts[] = [
                'id' => $concert->getId(),
                'artist' => $concert->getArtist(),
                'location' => $concert->getLocation(),
                'date' => date_format($concert->getDate(), 'd-m-Y'),
                'price' => $concert->getPrice() . ' €',
                'availability' => $concert->getAvailability(),
                'organizer' => $concert->getOrganizer()->getFullName(),
            ];
        }

        $result = [
            "concerts" => $concerts
        ];

        return new JsonModel($result);
    }

}

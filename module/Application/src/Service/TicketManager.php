<?php

namespace Application\Service;

use Application\Entity\Concert;
use Application\Entity\Ticket;

class TicketManager {

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
        
    }

}

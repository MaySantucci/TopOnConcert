<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TicketController extends AbstractActionController
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
     * @var \Application\Service\TicketManager
     */
    private $ticketManager;

    /**
     * @var \Application\Service\UserManager
     */
    private $userManager;

    public function __construct($entityManager, $concertManager, $userManager,$ticketManager)
    {
        $this->entityManager = $entityManager;
        $this->concertManager = $concertManager;
        $this->userManager = $userManager;
        $this->ticketManager = $ticketManager;
    }

    public function indexAction()
    {
        if ($this->userManager->isCustomer()) {
            $tickets = $this->userManager->getCurrentUser()->getTickets();
        } 

        $viewModel = new ViewModel();
        $viewModel->setTemplate('ticket/index');
        $viewModel->setVariable('tickets', $tickets);
        return $viewModel;
    }


    
}

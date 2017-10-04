<?php

namespace Application\Controller\Factory;

use Application\Service\UserManager;
use Application\Service\TicketManager;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Application\Service\ConcertManager;
use Application\Controller\TicketController;

class TicketControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $concertManager = $container->get(ConcertManager::class);
        $userManager = $container->get(UserManager::class);
        $ticketManager = $container->get(TicketManager::class);

        return new TicketController($entityManager, $concertManager, $userManager, $ticketManager);
    }

}

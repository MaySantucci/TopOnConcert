<?php

namespace Application\Service\Factory;

use Application\Service\ConcertManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\AuthenticationService;
use Application\Service\UserManager;

class TicketManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authenticationService = $container->get(AuthenticationService::class);
        $userManager = $container->get(UserManager::class);


        return new ConcertManager($entityManager, $authenticationService, $userManager);
    }

}

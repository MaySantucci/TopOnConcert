<?php

namespace Application\Controller\Factory;

use Application\Service\UserManager;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Application\Service\ConcertManager;
use Application\Controller\ConcertController;

class ConcertControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $concertManager = $container->get(ConcertManager::class);
        $userManager = $container->get(UserManager::class);

        return new ConcertController($entityManager, $concertManager, $userManager);
    }

}

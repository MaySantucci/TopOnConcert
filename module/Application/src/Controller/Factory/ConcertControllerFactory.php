<?php

namespace Application\Controller\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Application\Service\ConcertManager;
use Application\Controller\ConcertController;

class ConcertControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $concertManager = $container->get(ConcertManager::class);

        return new ConcertController($entityManager, $concertManager);
    }

}

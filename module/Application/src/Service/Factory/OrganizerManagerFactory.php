<?php

namespace Application\Service\Factory;

use Application\Service\OrganizerManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class OrganizerManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new OrganizerManager($entityManager);
    }

}

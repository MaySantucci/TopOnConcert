<?php

namespace Application\Service\Factory;

use Application\Service\UserManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

class UserManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionManager = $container->get(SessionManager::class);

        return new UserManager($entityManager, $sessionManager);
    }

}

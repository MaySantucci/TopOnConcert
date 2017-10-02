<?php

namespace Application\Service\Factory;

use Application\Service\AuthenticationService;
use Application\Service\UserManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

class UserManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionManager = $container->get(SessionManager::class);
        $authenticationService = $container->get(AuthenticationService::class);

        return new UserManager($entityManager, $sessionManager, $authenticationService);
    }

}

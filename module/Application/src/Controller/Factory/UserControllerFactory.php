<?php

namespace Application\Controller\Factory;

use Application\Service\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Application\Service\UserManager;
use Application\Controller\UserController;

class UserControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UserManager::class);
        $authenticationService = $container->get(AuthenticationService::class);

        return new UserController($entityManager, $userManager, $authenticationService);
    }

}

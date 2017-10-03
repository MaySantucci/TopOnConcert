<?php

namespace Application\View\Helper\Factory;

use Application\Service\AuthenticationService;
use Application\Service\UserManager;
use Application\View\Helper\Authentication;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthenticationFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $userManager = $container->get(UserManager::class);
        
        return new Authentication($userManager);
    }

}

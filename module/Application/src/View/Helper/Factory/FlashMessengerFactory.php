<?php

namespace Application\View\Helper\Factory;

use Application\View\Helper\Authentication;
use Application\View\Helper\FlashMessenger;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class FlashMessengerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $flashMessenger = $container->get(FlashMessenger::class);

        return new Authentication($flashMessenger);
    }

}

<?php

namespace Main\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Main\Controller\MainController;

class MainControllerFactory
{
    public function __invoke($container)
    {
        if ($container instanceof ServiceLocatorAwareInterface) {
            $container = $container->getServiceLocator();
        }
        $authService = $container->get('AuthService');
        $service = $container->get('Service');

        return new MainController($authService, $service);
    }

}

<?php

namespace Auth\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Auth\Controller\AuthController;

class AuthControllerFactory
{
    public function __invoke($container)
    {
        if ($container instanceof ServiceLocatorAwareInterface) 
        {
            $container = $container->getServiceLocator();
        }
        
        $authService    = $container->get('AuthService');
        $sTable         = $container->get('Auth\Model\AuthTable');

        return new AuthController($authService, $authService->getStorage(), $sTable);
    }

}

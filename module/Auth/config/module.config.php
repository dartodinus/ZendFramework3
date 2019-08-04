<?php

namespace Auth;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
  
   'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
        ],
    ],
    
    'router' => [
        'routes' => [
			'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            
            'auth' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/auth',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'authenticate',
                    ],
                ],
            ],
            
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
        ],
    ],
    
    'service_manager' => array(
        'aliases' => array(
            'Zend\Authentication\AuthenticationService' => 'AuthService',
        ),
        'factories' => array(
            'AuthService' => Service\AuthenticationFactory::class,
        ),
    ),
    
    'view_manager' => [
        
        'template_path_stack' => [
            'auth' => __DIR__ . '/../view',
        ],
    ],
];

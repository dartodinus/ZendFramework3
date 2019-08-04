<?php

namespace Main;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
	'controllers' => [
        'factories' => [
            //Controller\MainController::class => InvokableFactory::class,
            Controller\MainController::class => Controller\Factory\MainControllerFactory::class,
        ],
    ],
	
	'router' => [
        'routes' => [
            'main' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/main[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\MainController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    
    'view_manager' => [        
        'template_path_stack' => [
            'main' => __DIR__ . '/../view',
        ],
    ],
];

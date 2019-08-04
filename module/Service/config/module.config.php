<?php
/**
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Darto <dartodinus@gmail.com>
 * @contact		+62852-1414-1232
 * @package     ServiceModule
 */
namespace Service;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return array(
	
    'controllers' => [
        'factories' => [
            Controller\ServiceController::class => InvokableFactory::class,
        ],
    ],
    
    'router' => [
        'routes' => [
            'service' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/service[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ServiceController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
	
   'view_manager' => array(
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),


);
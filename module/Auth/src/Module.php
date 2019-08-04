<?php

namespace Auth;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Auth\Model\AuthTable;
use Service\Model\ServiceTable;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
	
    public function getServiceConfig()
    {
		return array(
			'factories' => array(
				'Auth\Model\AuthTable' =>  function($sm) {
					$dbAdapter = $sm->get('DbAdapter');
					$table = new AuthTable($dbAdapter);
					return $table;
				},
				
				'Privilege' => function($sm) {
					$dbAdapter      = $sm->get('DbAdapter');
					$auth 	        = new AuthTable($dbAdapter);
					return $auth;

				},
                
                'Service' => function($sm) {
					$dbAdapter      = $sm->get('DbAdapter');
					$auth 	        = new ServiceTable($dbAdapter);
					return $auth;

				},
			),
		);
        
    }   
}

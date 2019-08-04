<?php

namespace Application;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Db\Adapter\Adapter;
use Service\Model\ServiceTable;
use Service\Model\CustomCache;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function onBootstrap(MvcEvent $e)
    {        
        $eventManager = $e->getApplication()->getEventManager();
		$sharedManager = $e->getApplication()->getEventManager()->getSharedManager();
		$sm = $e->getApplication()->getServiceManager();

        //$session = new SessionManager();
        //$session->start();
        //$sessionManager = $sm->get(SessionManager::class);
        
		$sharedManager->attach('Zend\Mvc\Application', 'dispatch.error',
			 function($e) use ($sm) {
				if ($e->getParam('exception')){
					$ex = $e->getParam('exception');
					do {
						$sm->get('Logger')->crit(
							sprintf(
							   "%s:%d %s (%d) [%s]\n", 
								$ex->getFile(), 
								$ex->getLine(), 
								$ex->getMessage(), 
								$ex->getCode(), 
								get_class($ex)
							)
						);
					}
					while($ex = $ex->getPrevious());
				}
			 }
		);
		
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function($e) 
        {
            $result = $e->getResult();
            $result->setTerminal(TRUE);
        });
       
		$dbAdapter 		= $sm->get('DbAdapter');
        $serviceTable 	= new ServiceTable($dbAdapter);
        
        /** set constans roles*/
		$roles	= array(array('CODE'	=> 'VIEW', 'value' => 'view'),
						array('CODE'	=> 'ADD', 'value' => 'add'),
						array('CODE'	=> 'EDIT', 'value' => 'edit'),
						array('CODE'	=> 'DELETE', 'value' => 'delete'),
						array('CODE'	=> 'SHOW', 'value' => 'show'));
		
		foreach($roles as $key_roles){
			defined($key_roles['CODE'])
    			|| define($key_roles['CODE'], $key_roles['value']);
		}
       
		/** end set constans roles*/
        
        $restModule     = $serviceTable->getModules();
        foreach($restModule as $key_modules){
			defined($key_modules['code'])
    			|| define($key_modules['code'], $key_modules['id']);
		}
        
        /** set constans role modules view */
		if(isset($sm->get('AuthService')->getStorage()->read()->role_id)){
			$role_id = $sm->get('AuthService')->getStorage()->read()->role_id;
			
			foreach($restModule as $key_modules)
			{
				$roleView = (int)$sm->get('Privilege')->check($role_id, $key_modules['id'], VIEW);
				defined("VIEW_".$key_modules['code'])
					|| define("VIEW_".$key_modules['code'], $roleView);
			}
		}
		/** end set constans role modules view */

    }
	
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'DbAdapter' =>  function($sm) {
                    $config = $sm->get('config');
                    $config = $config['db'];
                    $dbAdapter = new Adapter($config);
                    return $dbAdapter;
                },
            ),
        );
    }
    
}

<?php
namespace Main\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Service\Model\ServiceTable;

class MainController extends AbstractActionController
{
    protected $auth;
    protected $service;
    
    public function __construct(AuthenticationService $authService, ServiceTable $service)
    {
        $this->auth = $authService;
        $this->service = $service;
    }

    public function indexAction()
    {
    	if(!$this->auth->hasIdentity()) {
    		return $this->redirect()->toRoute('login');
    	}
        
        $data['role_id'] = $this->auth->getStorage()->read()->role_id;
        $restMenu = $this->service->getMenu($data);
		
		$this->layout()->sMenu = $restMenu;
		$this->layout()->sCode = '10';
		
        $view   = new ViewModel();
		
		return $view;
    }
}

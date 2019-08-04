<?php
/**
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Darto <dartodinus@hotmail.com;dartodinus@gmail.com>
 * @contact		+62856-8-260684
 * @package     ServiceModule
 */

namespace Service\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
	Zend\Session\Container;
use Zend\Authentication\AuthenticationService;
	use Zend\Session\SessionManager;

	
class ServiceController extends AbstractActionController
{
    protected $userTable;	
    protected $storage;
    protected $authservice;
	
	public function __construct(AuthenticationService $authenticationService)
    {

    }
	
	function getAuthService() 
	{
		return $this->authService;
	}
    
    public function indexAction()
    {
		/**	Authentivication user login */
		//var_dump($this->getAuthService()->hasIdentity());
		exit();
    }	
	
}

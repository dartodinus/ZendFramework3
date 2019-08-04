<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Auth\Service\MyAuthStorage;
use Zend\Authentication\AuthenticationService;
use Auth\Model\AuthTable;

use Zend\Log\Writer\Stream,
	Zend\Log\Logger;
use Zend\Session\Container;

class AuthController extends AbstractActionController
{
	protected $sTable;
    protected $storage;
    protected $authService;
    
    public function __construct(AuthenticationService $authService, 
                                MyAuthStorage $storage, 
                                AuthTable $sTable)
    {
        $this->request	    = $this->getRequest();
        $this->authService  = $authService;
        $this->storage      = $storage;
        $this->sTable       = $sTable;
        
        /** Logs */
		$logsFiles		= date("Y_m_d").'_auth.txt';
		$this->rmkdir(LOGS_DIR.'access/'.date("Y").'/'.date("m").'/'.date("d"));
		$this->writer 	= new Stream(LOGS_DIR.'access/'.date("Y").'/'.date("m").'/'.date("d").'/'.$logsFiles);
		$this->logger 	= new Logger();
		$this->logger->addWriter($this->writer);
    }
	
	public function getAuthService()
    {
        //echo get_class($this->authService);die;
        return $this->authService;
    }

    public function getSessionStorage()
    {
        return $this->storage;
    }
	
	public function loginAction()
    {
        if ($this->getAuthService()->hasIdentity()) 
        {
            return $this->redirect()->toRoute('main');
        }
        
        $view   = new ViewModel([
			'form' => ''
		]);
        
        $view->setTerminal(true);
		return $view;
    }

    public function authenticateAction()
    {

        $redirect = 'login';
		$json["is_valid"] = 'true';
		
        if ($this->request->isPost()){
			
			if($this->request->getPost('username') == '' ||
			   $this->request->getPost('password') == ''){
				return $this->redirect()->toRoute($redirect);
				exit();
			}
             
			//check authentication...
			$this->getAuthService()->getAdapter()
								   ->setIdentity($this->request->getPost('username'))
								   ->setCredential($this->request->getPost('password'));
								   
			$result = $this->getAuthService()->authenticate();
			$messages = $result->getMessages();
            
			if ($result->isValid()) {
				$redirect = 'main';
                
				//check if it has rememberMe :
				if ($this->request->getPost('rememberme') == 1 ) {
					$this->getSessionStorage()->setRememberMe(1);
					//set storage again
					$this->getAuthService()->setStorage($this->getSessionStorage());
				}
				
				$data		= $this->getAuthService()->getAdapter()->getResultRowObject(null, 'password');
                $this->sTable->save($data);
               
                $user = (object) ['user_id' => $data->id,
                         'username'     => $data->username,
                         'name'         => $data->name,
                         'photo'        => $data->photo,
                         'secret_key'   => $data->secret_key,
                         'token_id'     => $this->sTable->getToken($data->id),
                         'is_admin'     => $data->is_admin,
                         'email'        => $data->email,
                         'role_id'      => $data->role_id];
				
                
				if($data->is_active == 1)
				{
					$this->getAuthService()->setStorage($this->getSessionStorage());
					$this->getAuthService()->getStorage()->write($user);

					$this->logger->info($this->request->getPost('username').' -- '.
										$_SERVER['REMOTE_ADDR'].' -- '.
										$_SERVER['HTTP_USER_AGENT'].' -- '.
										$_SERVER['REMOTE_PORT'].' -- '.
										$_SERVER['REQUEST_METHOD'].' -- '.
										$_SERVER['REQUEST_URI'].' -- '.
										$this->getAuthService()->getStorage()->read()->secret_key.' -- '.
										'login'. ' -- '.
										'success');

					$user_session = new Container('users');
					$user_session->cache_id    = $this->getAuthService()->getStorage()->read()->user_id;
					$user_session->username    = $this->getAuthService()->getStorage()->read()->username;
					$user_session->name        = $this->getAuthService()->getStorage()->read()->name;
					$user_session->photo       = $this->getAuthService()->getStorage()->read()->photo;
					
					$json["message_info"] = $redirect;
					echo json_encode($json);
					exit();
				}else{
					$this->logger->info($this->request->getPost('username').' -- '.
										$_SERVER['REMOTE_ADDR'].' -- '.
										'login'. ' -- '.
										'fail');
					
					$json["is_valid"] = 'false';
					$json["message_info"] = 'User yang anda gunakan belum teraktivasi';
					echo json_encode($json);
					exit();
				}
				
			}else{
			
				$this->logger->info($this->request->getPost('username').' -- '.
									$_SERVER['REMOTE_ADDR'].' -- '.
									'login'. ' -- '.
									'fail');
				
				$json["is_valid"] = 'false';
				$json["message_info"] = 'Silahkan cek kembali username atau password anda';
				echo json_encode($json);
				exit();
			} 
 
        }
  
        return $this->redirect()->toRoute($redirect);
    }
    
    public function logoutAction()
    {        
        if ($this->getAuthService()->hasIdentity()) {
            $this->getSessionStorage()->forgetMe();
			
			$this->logger->info($this->getAuthService()->getStorage()->read()->username.' -- '.
								$_SERVER['REMOTE_ADDR'].' -- '.
								'logout'. ' -- '.
								'success');
			
            $data = ['user_id'      => $this->getAuthService()->getStorage()->read()->user_id,
                     'username'     => $this->getAuthService()->getStorage()->read()->username,
                     'token_id'     => $this->getAuthService()->getStorage()->read()->token_id];
            
            $this->sTable->logout($data);	
            $this->getAuthService()->clearIdentity();
			$user_session = new Container('users');
			$user_session->getManager()->getStorage()->clear();
			
        }
        
        return $this->redirect()->toRoute('login');
    }
    
	public function lockAction()
	{
		$container = new Container("users");
		$view	= new ViewModel(array('user_id'    => $container->cache_id,
									  'username'   => $container->username,
									  'name'       => $container->name,
									  'photo'      => $container->photo));
		
		//$this->sTable->idle($this->getAuthService()->getStorage()->read()->id);
		$this->getAuthService()->clearIdentity();
		
		if(!$container->username)
		{
			return $this->redirect()->toRoute('main');
		}

		$view->setTerminal(true);
		return $view;
	}
	
	public function rmkdir($path, $mode = 0755) {

		$path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
		$e = explode("/", ltrim($path, "/"));
		if(substr($path, 0, 1) == "/") {
			$e[0] = "/".$e[0];
		}

		$c = count($e);
		$cp = $e[0];
		for($i = 1; $i < $c; $i++) {
			if(!is_dir($cp) && !@mkdir($cp, $mode)) {
				return false;
			}
			$cp .= "/".$e[$i];
		}
		return @mkdir($path, $mode);
	}

	/**
	public function logoutAction()
    {
        //$this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();         
        return $this->redirect()->toRoute('login');
    }
    */
}
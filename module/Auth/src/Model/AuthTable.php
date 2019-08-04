<?php
/**
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Darto <dartodinus@gmail.com>
 * @contact		+62852-1414-1232
 * @package     AuthModule
 */

namespace Auth\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select,
	Zend\Db\Sql\Sql,
	Zend\Db\Sql\Where,
	Zend\Db\Sql\TableIdentifier;

use Zend\Crypt\Key\Derivation\Pbkdf2,
	Zend\Math\Rand;

use Sinergi\BrowserDetector\Os,
    Sinergi\BrowserDetector\Browser;
	
class AuthTable extends AbstractTableGateway
{
    protected $table = 'users';
	protected $adapter;
	protected $connection;

    public function __construct(Adapter $adapter)
    {
        $this->adapter 		= $adapter;
        
		$this->sql 			= new Sql($this->adapter);
		$this->connection 	= $this->adapter->getDriver()->getConnection();
		
		$this->initialize();
    }
	
	public function getToken($userid)
	{
		
		$select 	= $this->sql->select();
		
		$select->from('users')
               ->columns(array("token_id"));
		
		$where 		= new  Where();
        $where->equalTo('id', $userid);
        $select->where($where);
		
		$sQuery 	= $this->sql->prepareStatementForSqlObject($select)->execute();
	
		$resultSet 	= new ResultSet();
		$result		= $resultSet->initialize($sQuery);
		
		if (!$result) {
            throw new \Exception("Could not find row");
        }
		
		return $result->current()->token_id;
		
	}
	
	public function save($param)
    {
        
		$salt 				= Rand::getBytes(32, true);
		$key  				= Pbkdf2::calc('sha256', $param->id, $salt, 10000, 10);
		$token_id 			= bin2hex($key);
		$Browser 			= new Browser($_SERVER['HTTP_USER_AGENT']);
		$Os 				= new Os($_SERVER['HTTP_USER_AGENT']);
		
        $activity = array(
            'username' 	    => $param->username,
            'activity_time' => date('Y-m-d H:i:s'),
			'host_addr'		=> $_SERVER['REMOTE_ADDR'],
			'detect_os'		=> $Os->getName()." ".$Os->getVersion(),
			'browser'		=> $Browser->getName()." ".$Browser->getVersion(),
            'activity'	    => 'login'
        );
        
        $data = array(
            'user_id' 	    => $param->id,
            'token_id'      => $token_id,
			'activities'	=> json_encode($activity),
        );
        
		$this->connection->beginTransaction();
		
		try {
            $sQueryActivity	 = $this->sql->insert('user_activities')->values($data);
			$sQueryUser	     = $this->sql->update('users')
									->set(array('token_id' => $token_id, 
                                                'login_status' => 1,
                                                'login_time' => date('Y-m-d H:i:s')))
									->where(array('id' => $param->id));
            
            //echo $setToken->getSqlString($this->adapter->getPlatform());
		    //exit();
            $this->sql->prepareStatementForSqlObject($sQueryActivity)->execute();
			$this->sql->prepareStatementForSqlObject($sQueryUser)->execute();
			
			$this->connection->commit();
			
		} catch (Exception $e) {
			$this->connection->rollback();
		}
		
    }
	
	public function logout($param)
    {
		$Browser 			= new Browser($_SERVER['HTTP_USER_AGENT']);
		$Os 				= new Os($_SERVER['HTTP_USER_AGENT']);
		
        $activity = array(
            'username' 	    => $param['username'],
            'activity_time' => date('Y-m-d H:i:s'),
			'host_addr'		=> $_SERVER['REMOTE_ADDR'],
			'detect_os'		=> $Os->getName()." ".$Os->getVersion(),
			'browser'		=> $Browser->getName()." ".$Browser->getVersion(),
            'activity'	    => 'logout'
        );
        
        $data = array(
            'user_id' 	    => $param['user_id'],
            'token_id'      => $param['token_id'],
			'activities'	=> json_encode($activity),
        );

		$this->connection->beginTransaction();
		try {
            $sQueryActivity	 = $this->sql->insert('user_activities')->values($data);
			$sQueryUser	     = $this->sql->update('users')
									->set(array('login_status' => 3,
                                                'logout_time'  => date('Y-m-d H:i:s')))
									->where(array('id' => $param['user_id']));
            
            //echo $setToken->getSqlString($this->adapter->getPlatform());
		    //exit();
            $this->sql->prepareStatementForSqlObject($sQueryActivity)->execute();
			$this->sql->prepareStatementForSqlObject($sQueryUser)->execute();
            
			$this->connection->commit();
			
		} catch (Exception $e) {
			$this->connection->rollback();
		}
		
    }
	
	/** Check previledge modules */
	public function check($role_id, $page_id, $do =NULL)
	{
		$select 	= $this->sql->select();
		
		$select->from('view_previledge');
		
		$where 		= new  Where();
        $where->equalTo('role_id', $role_id)
              ->equalTo('module_id', $page_id)
			  ->equalTo($do, 1);
			  
        $select->where($where);
		
		$sQuery 	= $this->sql->prepareStatementForSqlObject($select)->execute();
	
		$resultSet 	= new ResultSet();
		$result		= $resultSet->initialize($sQuery);
		
		if (!$result) {
            throw new \Exception("Could not find row");
        }
		
		$rows = array_values(iterator_to_array($result));
		if(count($rows) > 0 ){
			return true;
		}else{
			return false;
		}
	}

}

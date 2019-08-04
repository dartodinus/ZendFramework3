<?php
/**
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Darto <dartodinus@gmail.com>
 * @contact		+62852-1414-1232
 * @package     ServiceModule
 */

namespace Service\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select,
	Zend\Db\Sql\Sql,
	Zend\Db\Sql\Where,
	Zend\Db\Sql\Delete,
	Zend\Db\Sql\TableIdentifier;

use Service\Model\FunctionTable;

class ServiceTable extends AbstractTableGateway
{
    protected $table = "modules";
	protected $adapter;
	protected $connection;

    public function __construct(Adapter $adapter)
    {
        $this->adapter 		= $adapter;
        
		$this->sql 			= new Sql($this->adapter);
		$this->connection 	= $this->adapter->getDriver()->getConnection();
		
		$this->initialize();
    }
	
	/** get sequences */
	public function getSecId($sequenceName, $schema=NULL)
	{
		$select 	= $this->sql->select();
		if($schema != NULL)
		{
			$select->from(new TableIdentifier($sequenceName, $schema));
		}else{
			$select->from($sequenceName);
		}
		
		$sQuery 	= $this->sql->prepareStatementForSqlObject($select)->execute();
		
		$resultSet 	= new ResultSet();
		$result		= $resultSet->initialize($sQuery);
		
		if (!$result) {
            throw new \Exception("Could not find rows");
        }
		
		return $result->current()->last_value;
	}
	
	
	/** MODUL */
	public function getModules()
	{
		$select 	= $this->sql->select();
		
		$select->from('view_modules')
			   ->columns(array('id', 'ordinal', 'code'))
			   ->order('id ASC');
		
		$where 		= new  Where();
		$where->notEqualTo('parent', 0);
		$select->where($where);
			
		$sQuery 	= $this->sql->prepareStatementForSqlObject($select)->execute();
		$resultSet 	= new ResultSet();
		$result		= $resultSet->initialize($sQuery);
		
		if (!$result) {
            throw new \Exception("Could not find rows");
        }
		
		return $result->toArray();
		
	}
	/** END MODUL */
	
	/** MENU */
	public function getMenu($param = NULL)
	{
		$parent = $this->getParentMenu();
		$sMenu	 = array();
		foreach($parent as $val_menu)
		{
			$child 		= $this->getSubMenu($val_menu['id'], $param['role_id']);
			$sMenuChild	= array();
			foreach($child as $val_child)
			{
				/**	=================================================== */
				$subChild 		= $this->getSubSubMenu($val_child['id'], $param['role_id']);
				$sMenuSubChild	= array();
				foreach($subChild as $val_subchild)
				{
					$rowSubChild 	  = false;
					$sMenuSubChild[]  = array("Name"		=> $val_subchild['name'],
											  "Code"		=> $val_subchild['code'],
											  "Url"			=> ($rowSubChild == true ? "#" : $val_subchild['url']),
											  "IsChild"		=> $rowSubChild,
											  "Icon"		=> $val_subchild['icon'],
											  "Child"		=> "");
				}
				
				/**	=================================================== */
				$rowChild 	  = $this->checkSubSubMenu($val_child['id'], $param['role_id']);
				
				if($rowChild == 1 AND count($subChild) < 1):
				else:
					$sMenuChild[] = array("Name"		=> $val_child['name'],
										  "Code"		=> $val_child['code'],
										  "Url"  		=> ($rowChild == true ? "#" : $val_child['url']),
										  "IsChild"		=> $rowChild,
										  "Icon"   		=> $val_child['icon'],
										  "Child"   	=> $sMenuSubChild);
				endif;
			}
			
			/**	=================================================== */
			$rowMenu = $this->checkSubMenu($val_menu['id'], $param['role_id']);
			if($rowMenu == 1 AND count($child) < 1):
			else:
				$sMenu[] = array("Name"    		=> $val_menu['name'],
								 "Code"	   		=> $val_menu['code'],
								 "Url" 			=> ($rowMenu == true ? "#" : $val_menu['url']),
								 "IsChild"	   	=> $rowMenu,
								 "Icon"   		=> $val_menu['icon'],
								 "Child"   		=> $sMenuChild);
			endif;
		}
		
		return $sMenu;
	}
	
	public function getParentMenu()
	{
		$select 	= $this->sql->select();
		
		$select->from('view_menus')
			   ->columns(array('id', 'code', 'name', 'url', 'icon'))
			   ->order('ordinal ASC');
		
		$sQuery 	= $this->sql->prepareStatementForSqlObject($select)->execute();
		$resultSet 	= new ResultSet();
		$result		= $resultSet->initialize($sQuery);
		
		if (!$result) {
            throw new \Exception("Could not find rows");
        }
		
		return $result->toArray();
	}
	
	public function getSubMenu($parent, $role_id)
	{                
		$sql	= "SELECT id, 
						  code,
						  name,
						  url,
						  icon
				   FROM view_submenus
				   WHERE  (role_id = ".$role_id." AND
						   view = 1 OR module_id = 0) AND
						  parent = ".$parent." 
				   ORDER BY ordinal ASC";
		
		$sQuery 		= $this->adapter->query($sql)->execute();
		$rResult 		= new ResultSet();
		$rResultTotal  	= $rResult->initialize($sQuery);
		
		if (!$rResultTotal) {
			throw new \Exception("Could not find rows");
		}
		
		$result = array_values(iterator_to_array($rResultTotal));
		return $result;
	}
    
    public function getSubSubMenu($parent, $role_id)
	{                
		$sql	= "SELECT id, 
						  code,
						  name,
						  url,
						  icon
				   FROM view_sub_submenus
				   WHERE  (role_id = ".$role_id." AND
						   view = 1 OR module_id = 0) AND
						  parent = ".$parent." 
				   ORDER BY ordinal ASC";
		
		$sQuery 		= $this->adapter->query($sql)->execute();
		$rResult 		= new ResultSet();
		$rResultTotal  	= $rResult->initialize($sQuery);
		
		if (!$rResultTotal) {
			throw new \Exception("Could not find rows");
		}
		
		$result = array_values(iterator_to_array($rResultTotal));
		return $result;
	}
	
	public function checkSubMenu($parent, $role_id)
	{
		$select 	= $this->sql->select();
		
		$select->from('view_submenus')
			   ->columns(array('id'));
		
		$where 		= new  Where();
		$where->equalTo('parent', $parent);
        $where->equalTo('role_id', $role_id);
        $select->where($where);
		
		$sQuery 	= $this->sql->prepareStatementForSqlObject($select)->execute();
		$resultSet 	= new ResultSet();
		$result		= $resultSet->initialize($sQuery);
		
		if (!$result) {
            throw new \Exception("Could not find rows");
        }
		
		$rows = count(array_values(iterator_to_array($result)));
		if($rows > 0)
		{
			return true;
		}else{
			return false;
		}
	}
    
    public function checkSubSubMenu($parent, $role_id)
	{
		$select 	= $this->sql->select();
		
		$select->from('view_sub_submenus')
			   ->columns(array('id'));
		
		$where 		= new  Where();
		$where->equalTo('parent', $parent);
        $where->equalTo('role_id', $role_id);
        $select->where($where);
		
		$sQuery 	= $this->sql->prepareStatementForSqlObject($select)->execute();
		$resultSet 	= new ResultSet();
		$result		= $resultSet->initialize($sQuery);
		
		if (!$result) {
            throw new \Exception("Could not find rows");
        }
		
		$rows = count(array_values(iterator_to_array($result)));
		if($rows > 0)
		{
			return true;
		}else{
			return false;
		}
	}
	
	/** END MENU */
	
	/**	ROLES */
	public function fetchAllRoles()
	{	
		$select 	= $this->sql->select();
		
		$select->from('roles')
			   ->columns(array('id', 'name'))
		   	   ->order(array('name ASC'));
		
		$where 		= new  Where();
		
        $where->equalTo('is_active', 1);
        $select->where($where);
		
		$sQuery 	= $this->sql->prepareStatementForSqlObject($select)->execute();
		$resultSet 	= new ResultSet();
		$result		= $resultSet->initialize($sQuery);
		
		if (!$result) {
            throw new \Exception("Could not find rows");
        }
		
		return $result->toArray();
	}
	/**	END ROLES */
	
	public function isAuth($data)
	{	
		$select 	= $this->sql->select();
		
		$select->from("users")
			   ->columns(array('id'));
		
		$where 		= new  Where();
        $where->equalTo('username', $data['username'])
			  ->equalTo('password', md5($data['password']))
			  ->equalTo('is_active', 1);
        $select->where($where);
		
		$sQuery 	= $this->sql->prepareStatementForSqlObject($select)->execute();
		$resultSet 	= new ResultSet();
		$result		= $resultSet->initialize($sQuery);
		
		if (!$result) {
            throw new \Exception("Could not find rows");
        }
		
		$rows = count(array_values(iterator_to_array($result)));
		if($rows > 0)
		{
			return true;
		}else{
			return false;
		}
	}
	
	public function updateTimerUser($IDUSER)
	{
		$aColumns = array('TIMER' => date("Y-m-d H:i:s"));
		$this->connection->beginTransaction();
		try {
			$sQuery			= $this->sql->update("USERS_ACTIVITY")
										->set($aColumns)
										->where(array('IDUSER' => $IDUSER ));
			
			$sQueryTrigger	= $this->sql->update("USERS")
										->set($aColumns)
										->where(array('IDUSER' => $IDUSER ));
								
			$this->sql->prepareStatementForSqlObject($sQuery)->execute();
			$this->sql->prepareStatementForSqlObject($sQueryTrigger)->execute();
			$this->connection->commit();
			
		} catch (Exception $e) {
			$this->connection->rollback();
		}
	}
	
	public function testing()
	{	
		
		$sOrder = "ORDER BY  NAMAGROUP DESC";
		$sOrder = substr_replace( $sOrder, "", -2 );
		echo $sOrder;
		exit();
		$select 	= $this->sql->select();
		
		$select->from(array('a' => 'USERS'), 
					  array('IDUSER'))
			   ->join(array('b' => 'REF_IDENTITAS'), 'b.IDUSER = a.IDUSER',
			   		  array('NAMADEPAN'))
			   ->join(array('c' => 'GROUP'), 'c.IDGROUP = a.IDGROUP',
			   		  array('NAMAGROUP'));
		
		$where 		= new  Where();
        $where->equalTo('a.STATUS', 'A')
			  ->equalTo('a.IDUSER', '2102152248001');
			  
        $select->where($where);
		
		//$select->getSqlString($this->adapter->getPlatform());

		$sQuery 	= $this->sql->prepareStatementForSqlObject($select)->execute();
	
		$resultSet 	= new ResultSet();
		$result		= $resultSet->initialize($sQuery);
		
		if (!$result) {
            throw new \Exception("Could not find rows");
        }
		
		return $result->toArray();

	}

}

/**
$sWhere		 = " WHERE LOWER(".'"NIP"'.") = '".strtolower($data['NIP'])."' ";                  
$sql 		 = " SELECT ".'"IDPEGAWAI"'." 
                 FROM sdm.".'"PEGAWAI"'." ".
                 $sWhere;

$sQuery 		= $this->adapter->query($sql)->execute();
$rResult 		= new ResultSet();
$rResultTotal  	= $rResult->initialize($sQuery);

if (!$rResultTotal) {
    throw new \Exception("Could not find rows");
}

$rows = count(array_values(iterator_to_array($rResultTotal)));
return $rows;


$select->from(array('a' => 'REF_MENU'))
			   ->columns(array('IDMENU', 'KODE', 'NAMA', 'ROUTES', 'STYLES'))
					  
			   ->join(array('b' => 'REF_ROLES'), 'b.IDMODUL = a.IDMODUL',
			   		  array('IDROLE'), $select::JOIN_LEFT)
					  
			   ->order('a.KODE ASC');
		
		$where 		= new  Where();
		$where->equalTo('b.IDGROUP', 1);
		$where->equalTo('b.IDUSER', '2102152248001');
		$where->equalTo('b.ROLEVIEW', 1);
		$where->__get('OR')->equalTo('a.IDMODUL', 0);
		
		$where->equalTo('a.PARENT', $parent);
		$where->equalTo('a.LEVEL', $level);
        $where->equalTo('a.STATUS', 'A');
        $select->where($where);
*/

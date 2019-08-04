<?php

namespace Auth\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class UserTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
		$this->tableGateway->select(function(Select $select) {
			//$select->from('table');
			//$select->columns(array('id','category_name'));
			//$select->where->like('name', 'Brit%');
			//$select->order('name ASC')->limit(2);
			//$select->join('roles', 'roles.id = users.role_id', array('name'),'left');
			$select->join('roles', 'roles.id = users.role_id', array('roles'=>'name'),'left');
			//echo $select->getSqlString(); die;
		});
        return $this->tableGateway->select();
    }
	
    public function getUser($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if ($row) {
            return $row;
        }
		return [];        
    }
	
	public function getUserByUsername($username)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['username' => $username]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
           ));
        }

        return $row;
    }

    public function saveUser($user)
    {
		$data = [
			'user_login' => $user->user_login,
			'user_role' => $user->user_role,
			'user_nickname' => $user->user_nickname,
			'user_status' => $user->user_status,
			'branch_id' => $user->branch_id,
		];
		$id = (int) $user->id;

        if ($id === 0) {
			$data['user_pass'] = md5($user->user_pass);
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getUser($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update user with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }
	
	public function updateUser($data,$where)
    {
		return $this->tableGateway->update($data,$where);
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
	
}
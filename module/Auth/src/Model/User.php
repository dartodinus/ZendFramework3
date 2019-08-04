<?php

namespace Auth\Model;

use DomainException;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface
{
    public $id;
    public $username;
    public $password;
    public $name;
    public $telp;
    public $email;
    public $is_active;
	
	private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = ! empty($data['id']) ? $data['id'] : 0;
        $this->username = ! empty($data['username']) ? $data['username'] : null;
        $this->password = ! empty($data['password']) ? $data['password'] : null;
        $this->name  = ! empty($data['name']) ? $data['name'] : null;
        $this->telp  = ! empty($data['telp']) ? $data['telp'] : null;
        $this->email  = ! empty($data['email']) ? $data['email'] : null;
        $this->is_active  = ! empty($data['is_active']) ? $data['is_active'] : null;
    }
	
	public function getArrayCopy()
    {
        return [
            'id'        => $this->id,
            'username' 	=> $this->username,
            'password'  => $this->password,
            'name'  	=> $this->name,
            'telp'  	=> $this->telp,
            'email'  	=> $this->email,
            'is_active' => $this->is_active,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
		
		$isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;

        $inputFilter = new InputFilter();
		
		$inputFilter->add([
            'name' => 'username',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
		
		$inputFilter->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
							$isEmpty => 'Usre pass can not be empty.',
						],
                    ],
                ],
            ],
        ]);
		
		$inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 3,
                        'max' => 20,
                    ],
                ],
            ],
        ]);
		
		$inputFilter->add([
            'name' => 'telp',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
	
}
<?php
namespace Auth\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('login_validation1');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal1');
        $this->setAttribute('action', 'login');
		
		$this->setUseInputFilterDefaults(false);

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
		$this->add([
            'name' => 'username',
            'type' => 'text',
			'attributes' => [
				'class'             => 'form-control',
				'placeholder'       => 'Username',
                'data-rule-required'=> 'true',
                'size'              => '26',
                'minlength'         => '1',
			]
        ]);
        $this->add([
            'name' => 'password',
            'type' => 'password',
			'attributes' => [
                'class'             => 'form-control',
				'placeholder'       => 'Password',
                'data-rule-required'=> 'true',
                'size'              => '26',
                'minlength'         => '1',
            ],
        ]);
        $this->add([
            'name' => 'rememberme',
            'type' => 'checkbox',
			'attributes' => [
                'class'             => 'checkbox',
                'id'                => 'rememberme',
                'value'             => '1',
            ],
        ]);	
        $this->add([
            'name' => 'login',
            'type' => 'submit',
            'attributes' => [
                'class'	=> 'btn btn-success uppercase pull-right',
                'id'    => 'submit',
                'value' => 'Login',
            ],
        ]);
    }
	
}
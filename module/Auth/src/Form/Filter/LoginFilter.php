<?php 
namespace Auth\Form\Filter;
 
use Zend\InputFilter\InputFilter;
 
class LoginFilter extends InputFilter {
 
	public function __construct() {
		$isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
			
		$this->add([
            'name' => 'username',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim']
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            $isEmpty => 'Username can\'t be empty.'
                        ]
                    ]
                ]
            ]
        ]);
		
		$this->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim']
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            $isEmpty => 'Password can\'t be empty.'
                        ]
                    ]
                ],
            ]
        ]);
				
    }
}
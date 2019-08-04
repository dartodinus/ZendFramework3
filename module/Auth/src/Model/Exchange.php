<?php

namespace Auth\Model;

class Exchange {
	
	public $data = array();

    public function exchangeArray($data) {
		foreach($data as $index=>$value){
			$this->$index = $value;	
		}
    }
	
	public function __get($key) {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function __set($key, $value) {
        $this->data[$key] = $value;
    }

}

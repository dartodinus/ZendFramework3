<?php
/**
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Darto <dartodinus@gmail.com>
 * @contact		+62852-1414-1232
 * @package     ServiceModule
 */

namespace Service\Model;

use Cache;
	
class CustomCache extends Cache
{
	public function check_array_exists($array, $search)
	{
		$result = array();
		foreach ($array as $key => $value)
		{
			foreach ($search as $k => $v)
			{
				if (!isset($value[$k]) || $value[$k] != $v)
				{
					continue 2;
				}
			}
			$result[] = $key;
	
		}
		
		return $result;
	}
	  
	public function search($array, $key, $value)
	{
		$results = array();
	
		if (is_array($array)) {
			if (isset($array[$key]) && $array[$key] == $value) {
				$results[] = $array;
			}
			foreach ($array as $subarray) {
				$results = array_merge($results, $this->search($subarray, $key, $value));
			}
		}
		return $results;
	}
}


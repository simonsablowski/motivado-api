<?php

namespace Motivado\Api;

class CoachingConfigurator extends \Application {
	protected $values = array();
	
	public function __construct() {
		
	}
	
	public function getValues() {
		return $this->values;
	}
	
	public function getValue($field = NULL) {
		if (is_null($field)) return $this->getValues();
		
		return isset($this->values[$field]) ? $this->values[$field] : NULL;
	}
	
	public function setValues($values) {
		return $this->values = array_merge($this->getValues(), $values);
	}
	
	public function setValue($field, $value = NULL) {
		if (is_null($value)) return $this->setValues($field);
		
		return $this->values[$field] = $value;
	}
}
<?php

class TemporaryUser extends User {
	protected $fields = array(
		'id',
		'temporary'
	);
	protected $requiredFields = array();
	
	public static function getClassName() {
		return get_parent_class();
	}
	
	public function __construct() {
		parent::__construct(func_num_args() == 1 && is_array($argument = func_get_arg(0)) ? $argument : func_get_args());
		
		$this->setTemporary('yes');
	}
}
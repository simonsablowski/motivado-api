<?php

class User extends Model {
	protected $fields = array(
		'id',
		'eMailAddress',
		'password',
		'firstName',
		'lastName',
		'temporary',
		'status',
		'created',
		'modified'
	);
	protected $hiddenFields = array(
		'id',
		'password',
		'status',
		'created',
		'modified'
	);
	
	public function __construct() {
		parent::__construct(func_num_args() == 1 && is_array($argument = func_get_arg(0)) ? $argument : func_get_args());
		
		$this->setTemporary('no');
	}
	
	//TODO
	public function isSuitableForObject(Object $Object) {
		return FALSE;
	}
}
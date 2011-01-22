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
		parent::__construct();
		
		$this->setTemporary('yes');
	}
}
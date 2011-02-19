<?php

class UsersInteraction extends Model {
	protected static $primaryKey = array(
		'UserId',
		'ObjectId'
	);
	protected $fields = array(
		'UserId',
		'ObjectId',
		'key',
		'value',
		'result',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'UserId',
		'ObjectId',
		'key',
		'value',
		'result'
	);
	
	protected $User = NULL;
	protected $Object = NULL;
}
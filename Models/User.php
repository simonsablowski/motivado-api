<?php

class User extends Model {
	protected $fields = array(
		'id',
		'firstName',
		'lastName',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'firstName'
	);
}
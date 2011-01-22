<?php

class User extends Object {
	protected $fields = array(
		'id',
		'eMailAddress',
		'password',
		'firstName',
		'lastName',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'firstName',
		'lastName'
	);
}
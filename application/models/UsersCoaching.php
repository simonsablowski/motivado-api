<?php

class UsersCoaching extends Model {
	protected static $primaryKey = array(
		'UserId',
		'CoachingId'
	);
	protected $fields = array(
		'UserId',
		'CoachingId',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'UserId',
		'CoachingId'
	);
	
	protected $User = NULL;
	protected $Coaching = NULL;
}
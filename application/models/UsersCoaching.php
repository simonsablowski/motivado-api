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
	
	//TODO: Coaching is not loaded by $UsersCoaching->getCoaching()
	protected $User = NULL;
	protected $Coaching = NULL;
}
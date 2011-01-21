<?php

class ObjectDependency extends Model {
	protected $tableName = 'objectdependency';
	protected $primaryKey = array(
		'CoachingId',
		'LeftId',
		'RightId'
	);
	protected $fields = array(
		'CoachingId',
		'LeftId',
		'RightId',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'CoachingId',
		'LeftId',
		'RightId'
	);
	
	protected $Coaching = NULL;
}
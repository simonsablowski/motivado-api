<?php

class ObjectSequence extends Model {
	protected $tableName = 'objectsequence';
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
		'RightId'
	);
	
	protected $Coaching = NULL;
}
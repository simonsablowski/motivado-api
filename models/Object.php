<?php

class Object extends Model {
	protected $fields = array(
		'id',
		'CoachingId',
		'type',
		'key',
		'properties',
		'title',
		'description',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'CoachingId',
		'type',
		'key'
	);
	protected $hiddenFields = array(
		'id',
		'CoachingId',
		'type',
		'status',
		'created',
		'modified'
	);
	
	protected $Coaching = NULL;
}
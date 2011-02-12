<?php

class Object extends Model {
	protected $fields = array(
		'id',
		'CoachingId',
		'type',
		'title',
		// 'data',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'CoachingId',
		'type',
		'title',
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
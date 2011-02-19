<?php

class ObjectTransition extends Model {
	protected static $primaryKey = array(
		'CoachingId',
		'LeftId',
		'RightId'
	);
	protected $fields = array(
		'CoachingId',
		'LeftId',
		'RightId',
		'condition',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'CoachingId',
		'RightId'
	);
	
	protected $Coaching = NULL;
	protected $Left = NULL;
	protected $Right = NULL;
	
	protected function loadLeft() {
		return $this->setLeft(Object::find($this->getLeftId()));
	}
	
	protected function loadRight() {
		return $this->setRight(Object::find($this->getRightId()));
	}
}
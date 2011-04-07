<?php

class ObjectTransition extends Model {
	protected static $primaryKey = array(
		'CoachingId',
		'LeftId',
		'RightId',
		'condition'
	);
	protected static $defaultSorting = NULL;
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
		try {
			return $this->setLeft(Object::find($this->getLeftId()));
		} catch (Error $Error) {
			return $this->setLeft(NULL);
		}
	}
	
	protected function loadRight() {
		try {
			return $this->setRight(Object::find($this->getRightId()));
		} catch (Error $Error) {
			return $this->setLeft(NULL);
		}
	}
}
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
	
	public static function findNextObjects($Object, $data = array()) {
		$ObjectTransitions = self::findAll(array_merge(array(
			'CoachingId' => $Object->getCoachingId(),
			'LeftId' => $Object->getId()
		), $data));
		
		$NextObjects = array();
		foreach ($ObjectTransitions as $ObjectTransition) {
			$NextObjects[] = Object::find($ObjectTransition->getRightId());
		}
		return $NextObjects;
	}
}
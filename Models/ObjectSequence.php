<?php

class ObjectSequence extends Model {
	protected static $primaryKey = array(
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
	
	public static function findNextObjects($Object) {
		$ObjectSequences = self::findAll(array(
			'CoachingId' => $Object->getCoachingId(),
			'LeftId' => $Object->getId()
		));
		
		$NextObjects = array();
		foreach ($ObjectSequences as $ObjectSequence) {
			$NextObjects[] = Object::find($ObjectSequence->getRightId());
		}
		return $NextObjects;
	}
}
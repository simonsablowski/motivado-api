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
	
	public static function findNextObjects($Object, $data = array()) {
		$ObjectSequences = self::findAll(array_merge(array(
			'CoachingId' => $Object->getCoachingId(),
			'LeftId' => $Object->getId()
		), $data));
		
		$NextObjects = array();
		foreach ($ObjectSequences as $ObjectSequence) {
			$NextObject = ObjectFinder::find($ObjectSequence->getRightId());
			$NextObjects[] = $NextObject;
		}
		
		return $NextObjects;
	}
}
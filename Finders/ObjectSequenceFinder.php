<?php

class ObjectSequenceFinder extends Finder {
	protected static $tableName = 'objectsequence';
	
	public static function findNextObjects($Object, $data = array()) {
		$ObjectSequences = self::findAll(array_merge(array(
			'CoachingId' => $Object->getCoachingId(),
			'LeftId' => $Object->getId()
		), $data));
		
		$NextObjects = array();
		foreach ($ObjectSequences as $ObjectSequence) {
			$NextObject = ObjectFinder::find($RightId = $ObjectSequence->getRightId());
			$NextObjects[] = $NextObject;
		}
		
		return $NextObjects;
	}
}
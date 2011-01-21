<?php

class ObjectSequenceFinder extends Finder {
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
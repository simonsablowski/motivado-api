<?php

class Coaching extends Object {
	protected $fields = array(
		'id',
		'OriginalId',
		'language',
		'title',
		'description',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'language',
		'title'
	);
	
	protected $Objects = NULL;
	protected $FirstObject = NULL;
	
	protected function getReferenceId() {
		return $this->getOriginalId() ? $this->getOriginalId() : $this->getId();
	}
	
	protected function loadObjects() {
		$ObjectSequenceObjects = ObjectSequenceFinder::findAll(array(
			'CoachingId' => $this->getReferenceId()
		));
		$Objects = array();
		foreach ($ObjectSequenceObjects as $ObjectSequenceObject) {
			$Objects[] = ObjectFinder::find($ObjectSequenceObject->getRightId());
		}
		$this->setObjects($Objects);
	}
		
	protected function loadFirstObject() {
		$ObjectSequence = ObjectSequenceFinder::findFirst(array(
			'CoachingId' => $this->getId(),
			'LeftId' => 0
		));
		$this->setFirstObject(ObjectFinder::find($ObjectSequence->getRightId()));
	}
}
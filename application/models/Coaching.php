<?php

class Coaching extends Object {
	protected $fields = array(
		'id',
		'OriginalId',
		'key',
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
		$ObjectTransitions = ObjectTransition::findAll(array(
			'CoachingId' => $this->getReferenceId()
		));
		$Objects = array();
		foreach ($ObjectTransitions as $ObjectTransition) {
			try {
				$Objects[] = Object::find($ObjectTransition->getRightId());
			} catch (Error $Error) {
				continue;
			}
		}
		$this->setObjects($Objects);
	}
		
	protected function loadFirstObject() {
		$ObjectTransition = ObjectTransition::findFirst(array(
			'CoachingId' => $this->getId(),
			'LeftId' => 0
		));
		$this->setFirstObject(Object::find($ObjectTransition->getRightId()));
	}
}
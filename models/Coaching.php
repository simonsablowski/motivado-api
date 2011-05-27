<?php

namespace Motivado\Api;

class Coaching extends \Model {
	protected static $defaultSorting = array(
		'title' => 'ascending'
	);
	protected $fields = array(
		'id',
		'key',
		'language',
		'title',
		'description',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'key'
	);
	protected $hiddenFields = array(
		'id',
		'status',
		'created',
		'modified'
	);
	
	protected $Objects = NULL;
	protected $FirstObject = NULL;
	
	protected function loadObjects() {
		$ObjectTransitions = ObjectTransition::findAll(array(
			'CoachingId' => $this->getId()
		));
		$Objects = array();
		foreach ($ObjectTransitions as $ObjectTransition) {
			try {
				if (!in_array($Object = Object::find($ObjectTransition->getRightId()), $Objects)) {
					$Objects[] = $Object;
				}
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
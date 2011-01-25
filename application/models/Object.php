<?php

class Object extends Model {
	protected $fields = array(
		'id',
		'CoachingId',
		'type',
		'title',
		// 'data',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'CoachingId',
		'type',
		'title',
	);
	protected $hiddenFields = array(
		'id',
		'CoachingId',
		'type',
		'status',
		'created',
		'modified'
	);
	
	protected $Coaching = NULL;
	protected $NextObject = NULL;
	protected $NextObjects = NULL;
	protected $condition = NULL;
	
	protected function loadNextObjects($condition = array()) {
		$this->setNextObjects(ObjectTransition::findNextObjects($this, $condition));
	}
	
	public function getNextObjects($condition = array()) {
		if (is_null($this->NextObjects)) $this->loadNextObjects($condition);
		return $this->NextObjects;
	}
	
	protected function loadNextObject($User, $condition = array()) {
		$NextObjects = $this->getNextObjects($condition);
		
		if (count($NextObjects) == 1) {
			$this->setNextObject(pos($NextObjects));
		} else {
			$this->loadSuitableNextObject($User);
		}
	}
	
	protected function loadSuitableNextObject($User) {
		$UnconditionalObjects = array();
		
		foreach ($this->getNextObjects() as $NextObject) {
			if (!$NextObject->hasCondition()) {
				$UnconditionalObjects[] = $NextObject;
			}
			
			if ($User->isSuitableForObject($NextObject)) {
				$this->setNextObject($NextObject);
				return;
			}
		}
		
		$this->setNextObject($UnconditionalObjects ? pos($UnconditionalObjects) : NULL);
	}
	
	public function getNextObject($User, $condition = array()) {
		if (is_null($this->NextObject)) $this->loadNextObject($User, $condition);
		return $this->NextObject;
	}
}
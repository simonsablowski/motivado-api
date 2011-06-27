<?php

namespace Motivado\Api;

class CoachingController extends \Controller {
	protected $CoachingHistory = NULL;
	protected $ConditionEvaluator = NULL;
	
	protected function getStartObject(Coaching $Coaching, $initial = TRUE) {
		if (($CurrentObject = $this->getCoachingHistory()->getCurrentObject($Coaching)) &&
				!$initial && ($NextObject = $this->getNextObject($CurrentObject))) {
			return $NextObject;
		} else if ($CurrentObject) {
			return $CurrentObject;
		}
		return $Coaching->getFirstObject();
	}
	
	protected function getNextObject(Object $Object) {
		$ObjectTransitions = ObjectTransition::findAll(array(
			'LeftId' => $Object->getId()
		));
		
		$NextObjects = array();
		foreach ($ObjectTransitions as $ObjectTransition) {
			if (!$NextObject = $ObjectTransition->getRight()) {
				continue;
			}
			
			if ($condition = $ObjectTransition->getCondition()) {
				if ($this->getConditionEvaluator()->evaluate($condition)) {
					return $NextObject;
				}
			} else {
				$NextObjects[] = $NextObject;
			}
		}
		
		return count($NextObjects) == 1 ? pos($NextObjects) : NULL;
	}
	
	protected function setupCoachingHistory() {
		if ($this->getCoachingHistory()) return;
		
		$this->setCoachingHistory(new CoachingHistory);
		$this->getCoachingHistory()->setSession($this->getSession());
	}
	
	protected function setupConditionEvaluator() {
		if ($this->getConditionEvaluator()) return;
		
		$this->setConditionEvaluator(new ConditionEvaluator);
	}
	
	public function query($CoachingKey, $initial = TRUE) {
		$this->setupCoachingHistory();
		$this->setupConditionEvaluator();
		
		$Coaching = Coaching::findByKey($CoachingKey);
		$Object = $this->getStartObject($Coaching, (bool)$initial);
		
		$endReached = FALSE;
		if (!$this->getNextObject($Object)) {
			$endReached = TRUE;
		}
		
		$Objects = array();
		while (!is_null($Object)) {
			$Objects[] = $Object;
			$Object = $this->getNextObject($Object);
		}
		
		return $this->displayView('Coaching.query.php', array(
			'ObjectSequence' => new ObjectSequence($Coaching, $Objects, $endReached)
		));
	}
	
	public function extendCoachingHistory($CoachingKey, $ObjectId) {
		$this->setupCoachingHistory();
		
		return $this->getCoachingHistory()->extend(Coaching::findByKey($CoachingKey), Object::find($ObjectId));
	}
}
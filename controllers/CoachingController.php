<?php

namespace Motivado\Api;

class CoachingController extends \Controller {
	protected $CoachingHistory = NULL;
	protected $ConditionEvaluator = NULL;
	protected $evaluatedConditions = array();
	
	protected function getStartObject(Coaching $Coaching, $initial = TRUE) {
		if (($CurrentObject = $this->getCoachingHistory()->getCurrentObject($Coaching)) && !$initial) {
			return $this->getNextObject($Coaching, $CurrentObject, TRUE);
		} else if ($CurrentObject) {
			return $CurrentObject;
		}
		return $Coaching->getFirstObject();
	}
	
	protected function getNextObject(Coaching $Coaching, Object $Object, $start = FALSE) {
		$ObjectTransitions = ObjectTransition::findAll(array(
			'LeftId' => $Object->getId()
		));
		
		$NextObjects = array();
		$IndependentNextObjects = array();
		foreach ($ObjectTransitions as $ObjectTransition) {
			$id = ($NextObject = $ObjectTransition->getRight()) ? $NextObject->getId() : NULL;
			$NextObjects[$id] = $NextObject;
			
			if ($condition = $ObjectTransition->getCondition()) {
				if ($this->getConditionEvaluator()->evaluate($condition)) {
					if (!in_array($condition, $this->evaluatedConditions)) {
						$this->evaluatedConditions[] = $condition;
						return $NextObject;
					} else {
						return FALSE;
					}
				}
			} else {
				$IndependentNextObjects[$id] = $NextObject;
			}
		}
		
		if (count($NextObjects) == 1) {
			return pos($NextObjects);
		} else if ($start && count($NextObjects) > 1 && count($IndependentNextObjects) > 0) {
			return pos($IndependentNextObjects);
		} else if (count($NextObjects) > 1) {
			return FALSE;
		} else {
			return NULL;
		}
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
		$endReached = is_null($Object) || (is_object($Object) && is_null($this->getNextObject($Coaching, $Object)));
		
		$Objects = array();
		while (is_object($Object)) {
			$Objects[] = $Object;
			$Object = $this->getNextObject($Coaching, $Object);
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
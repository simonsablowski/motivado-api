<?php

class CoachingController extends Controller {
	protected $restrictAccess = TRUE;
	protected $CoachingHistory = NULL;
	protected $ConditionEvaluator = NULL;
	
	protected function getStartObject(Coaching $Coaching) {
		if ($StartObject = $this->getCoachingHistory()->getCurrentObject($Coaching)) {
			return $StartObject;
		}
		return $Coaching->getFirstObject();
	}
	
	protected function getNextObject(Object $Object) {
		$ObjectTransitions = ObjectTransition::findAll(array(
			'CoachingId' => $Object->getCoachingId(),
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
			}
			
			$NextObjects[] = $NextObject;
		}
		
		return $NextObjects ? pos($NextObjects) : NULL;
	}
	
	public function query($CoachingKey) {
		$this->setupCoachingHistory();
		$this->setupConditionEvaluator();
		
		$Coaching = Coaching::findByKey($CoachingKey);
		$Object = $this->getStartObject($Coaching);
		
		$Objects = array();
		while (!is_null($Object)) {
			if ($Object->getType() != 'SignUp' || $this->getRestrictAccess()) {
				$Objects[] = $Object;
				
				if ($Object->getType() == 'SignUp' || $Object->getType() == 'Interrupt' || $Object->getType() == 'Coaching') {
					break;
				}
			}
			
			$Object = $this->getNextObject($Object);
		}
		
		$this->displayView('Coaching.query.php', array(
			'Coaching' => $Coaching,
			'Objects' => $Objects
		));
	}
	
	protected function setupCoachingHistory() {
		$this->setCoachingHistory(new CoachingHistory);
		$this->getCoachingHistory()->setSession($this->getSession());
	}
	
	public function extendCoachingHistory($CoachingKey, $ObjectId) {
		return $this->getCoachingHistory()->extend(Coaching::findByKey($CoachingKey), Object::find($ObjectId));
	}
	
	protected function setupConditionEvaluator() {
		$this->setConditionEvaluator(new ConditionEvaluator);
	}
}
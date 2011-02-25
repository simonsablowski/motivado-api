<?php

class CoachingController extends Controller {
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
			try {
				$NextObject = $ObjectTransition->getRight();
				
				if ($condition = $ObjectTransition->getCondition()) {
					if ($this->getConditionEvaluator()->evaluate($condition)) {
						return $NextObject;
					}	
				} else {
					$NextObjects[] = $NextObject;
				}
			} catch (Error $Error) {
				continue;
			}
		}
		
		return $NextObjects ? pos($NextObjects) : NULL;
	}
	
	public function query($CoachingKey) {
		$this->setupCoachingHistory();
		$this->setupConditionEvaluator();
		
		$Coaching = Coaching::findByKey($CoachingKey);
		$Object = $this->getStartObject($Coaching);
		
		//TODO: of course this should not be configurable via url parameters
		$restrictAccess = func_num_args() > 1 ? (bool)func_get_arg(1) : FALSE;
		
		$Objects = array();
		while (!is_null($Object)) {
			if ($Object->getType() != 'SignUp' || !$restrictAccess) {
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
<?php

class CoachingController extends Controller {
	protected $CoachingHistory = NULL;
	
	protected function getStartObject(Coaching $Coaching) {
		if ($StartObject = $this->getCoachingHistory()->getCurrentObject($Coaching)) {
			return $StartObject;
		}
		return $Coaching->getFirstObject();
	}
	
	//TODO: implement logic after a technical concept for conditions was developed
	protected function meetsCondition($condition) {
		return TRUE;
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
				
				//TODO: fit call to updated method
				if ($condition = $ObjectTransition->getCondition()) {
					if ($this->meetsCondition($condition)) {
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
		$this->initializeCoachingHistory();
		
		$Coaching = Coaching::findByKey($CoachingKey);
		$Object = $this->getStartObject($Coaching);
		
		$Objects = array();
		while (!is_null($Object)) {
			if ($Object->getType() != 'SignUp' || $this->restrictAccess($CoachingKey)) {
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
	
	//TODO: implement gateway for evaluating whether the user is paying customer for the requested product
	protected function restrictAccess($CoachingKey) {
		return FALSE;
	}
	
	protected function initializeCoachingHistory() {
		$this->setCoachingHistory(new CoachingHistory);
		$this->getCoachingHistory()->setSession($this->getSession());
	}
	
	public function extendCoachingHistory($CoachingKey, $ObjectId) {
		return $this->getCoachingHistory()->extend(Coaching::findByKey($CoachingKey), Object::find($ObjectId));
	}
}
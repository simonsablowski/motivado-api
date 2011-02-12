<?php

class CoachingController extends UserInteractionController {
	protected $CoachingHistory = NULL;
	
	protected function getStartObject(Coaching $Coaching) {
		if ($StartObject = $this->getCoachingHistory()->getCurrentObject($Coaching)) {
			return $StartObject;
		}
		return $Coaching->getFirstObject();
	}
	
	//TODO
	protected function isSuitableObject(Object $Object) {
		return FALSE;
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
				$NextObjects[] = $NextObject;
				
				if ($this->isSuitable($NextObject)) {
					return $NextObject;
				}
			} catch (Error $Error) {
				continue;
			}
		}
		
		return $NextObjects ? pos($NextObjects) : NULL;
	}
	
	public function query($key, $useCoachingHistory = TRUE) {
		$this->updateUser();
		$this->setCoachingHistory(new CoachingHistory((bool)$useCoachingHistory && $this->isSignedIn()));
		$this->getCoachingHistory()->setSession($this->getSession());
		
		$Coaching = Coaching::findByKey($key);
		$Object = $this->getStartObject($Coaching);
		
		$Objects = array();
		while (!is_null($Object)) {
			if ($Object->getType() != 'SignUp' || !$this->isSignedIn()) {
				$Objects[] = $Object;
				
				if ($Object->getType() == 'SignUp' || $Object->getType() == 'Interrupt' || $Object->getType() == 'Coaching') {
					break;
				}
			}
			
			if ($Object = $this->getNextObject($Object)) {
				$this->getCoachingHistory()->extend($Coaching, $Object);
			}
		}
		
		$this->displayView('Coaching.query.php', array(
			'Coaching' => $Coaching,
			'Objects' => $Objects
		));
	}
}
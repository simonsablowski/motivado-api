<?php

class CoachingController extends UserInteractionController {
	protected function getCoachingHistory($Coaching = NULL) {
		if (!$this->isSignedIn()) return NULL;
		
		$CoachingHistory = $this->getSession()->getData('CoachingHistory');
		if (is_null($Coaching)) {
			return $CoachingHistory;
		} else if ($Coaching instanceof Coaching && is_array($CoachingHistory) && isset($CoachingHistory[$Coaching->getId()])) {
			return $CoachingHistory[$Coaching->getId()];
		} else {
			return array();
		}
	}
	
	protected function extendCoachingHistory(Coaching $Coaching, Object $Object) {
		if (!$this->isSignedIn()) return NULL;
		
		$CoachingHistory = $this->getCoachingHistory();
		$CoachingHistory[$Coaching->getId()][] = $Object;
		return $this->getSession()->setData('CoachingHistory', $CoachingHistory);
	}
	
	protected function cleanCoachingHistory() {
		return $this->getSession()->setData('CoachingHistory', NULL);
	}
	
	protected function getCurrentObject(Coaching $Coaching) {
		$CoachingHistory = $this->getCoachingHistory($Coaching);
		return is_array($CoachingHistory) ? end($CoachingHistory) : NULL;
	}
	
	protected function getStartObject(Coaching $Coaching) {
		if ($StartObject = $this->getCurrentObject($Coaching)) {
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
	
	public function query($key) {
		$this->updateUser();
		
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
				$this->extendCoachingHistory($Coaching, $Object);
			}
		}
		
		$this->displayView('Coaching.query.php', array(
			'Coaching' => $Coaching,
			'Objects' => $Objects
		));
	}
}
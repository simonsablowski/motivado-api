<?php

class CoachingController extends UserInteractionController {
	protected function getStartObject(Coaching $Coaching) {
		if ($StartObject = $this->getUser()->getCurrentCoachingObject($Coaching)) {
			return $StartObject;
		} else {
			return $Coaching->getFirstObject();
		}
	}
	
	public function query($key) {
		$this->updateUser();
		
		$Coaching = Coaching::findByKey($key);
		$Object = $this->getStartObject($Coaching);
		
		if ($Object->getType() == 'Coaching') {
			$Coaching = $Object;
		}
		
		$Objects = array();
		while (!is_null($Object)) {
			if ($Object->getType() != 'SignUp' || !$this->isSignedIn()) {
				$Objects[] = $Object;
				
				if ($Object->getType() == 'SignUp' || $Object->getType() == 'Interrupt' || $Object->getType() == 'Coaching') {
					break;
				}
			}
			
			$Object = $Object->getNextObject($this->getUser());
		}
		
		$this->displayView('Coaching.query.php', array(
			'Coaching' => $Coaching,
			'Objects' => $Objects
		));
	}
}
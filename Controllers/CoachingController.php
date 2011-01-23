<?php

class CoachingController extends Controller {
	public function query($key) {
		$this->updateUser();
		
		$Coaching = Coaching::findByKey($key);
		$Object = $Coaching->getFirstObject();
		
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
			
			$Object = $Object->getNextObject($this->getUser()->getId());
		}
		
		$this->displayView('Coaching.query.xml', array(
			'Coaching' => $Coaching,
			'Objects' => $Objects
		));
	}
}
<?php

class CoachingController extends Controller {
	public function query($key) {
		$Coaching = Coaching::findByKey($key);
		$UserId = $this->getUser()->getId();
		
		$this->printLine("<objectsequence>");
		$CurrentObject = $Coaching->getFirstObject();
		
		if ($CurrentObject->getType() == 'Coaching') {
			$Coaching = $CurrentObject;
		}
		
		$this->printLine("\t<title>%s</title>", $Coaching->getTitle());
		$this->printLine("\t<description>%s</description>", $Coaching->getDescription());
		
		while (!is_null($CurrentObject)) {
			if (($CurrentObject->getType() != 'SignIn' && $CurrentObject->getType() != 'Payment') || !$this->isSignedIn()) {
				$CurrentObject->dump();
				
				if ((($CurrentObject->getType() == 'SignIn' || $CurrentObject->getType() == 'Payment') && !$this->isSignedIn())
					|| $CurrentObject->getType() == 'Interrupt' || $CurrentObject->getType() == 'Coaching') {
					break;
				}
			}
			
			$Ancestor = $CurrentObject;
			$CurrentObject = $Ancestor->getNextObject($UserId);
		}
		
		$this->printLine("</objectsequence>");
	}
}
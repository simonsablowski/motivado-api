<?php

class CoachingController extends Controller {
	protected function getStartObject($CoachingId) {
		return CoachingFinder::find($CoachingId)->getFirstObject();
	}
	
	public function query($key) {
		$Coaching = CoachingFinder::findByKey($key);
		$UserId = $this->getUser()->getId();
		
		$this->printLine("<objectsequence>");
		$CurrentObject = $this->getStartObject($Coaching->getId());
		
		if ($CurrentObject->getType() == 'Coaching') {
			$Coaching = $CurrentObject;
		}
		
		$this->printLine("\t<title>%s</title>", $Coaching->getTitle());
		$this->printLine("\t<description>%s</description>", $Coaching->getDescription());
		
		while (!is_null($CurrentObject)) {
			if (($CurrentObject->getType() == 'SignIn' || $CurrentObject->getType() == 'Payment') && isSignedIn()) {
				$CurrentObject = $CurrentObject->getNextObject($UserId);
			}
			
			$CurrentObject->dump();
			
			if (($CurrentObject->getType() == 'SignIn' || $CurrentObject->getType() == 'Payment') && !isSignedIn()) {
				break;
			} else if ($CurrentObject->getType() == 'Interrupt') {
				if ($interruptText = $CurrentObject->getText()) $this->getSession()->setData('interruptText', $interruptText);
				break;
			} else if ($CurrentObject->getType() == 'Questionnaire' && $CurrentObject->isActivated($UserId)) {
				break;
			}
			
			$Ancestor = $CurrentObject;
			$CurrentObject = $Ancestor->getNextObject($UserId);
			
			if (!is_null($CurrentObject) && $CurrentObject->getType() == 'Coaching') {
				break;
			}
		}
		
		$this->printLine("</objectsequence>");
		$this->getOutputBuffer()->flush();
	}
}
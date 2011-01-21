<?php

class CoachingController extends Controller {
	protected function getStartObject($CoachingId) {
		return CoachingFinder::find($CoachingId)->getFirstObject();
	}
	
	public function dumpCoachingPath($key) {
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
			/*if ($CurrentObject->getType() == 'Coaching') {
				$Coaching = $CurrentObject;
				//$CurrentObject = $this->handleCoaching($CurrentObject, $UserId);
				$CurrentObject = $this->handleCoaching(CoachingFinder::findByTitle($CurrentObject->getTitle()), $UserId);//TODO: silly code, just for testing
				
				if (is_null($CurrentObject)) break;
			}*/
			
			if (($CurrentObject->getType() == 'SignIn' || $CurrentObject->getType() == 'Payment') && isSignedIn()) {
				$CurrentObject = $CurrentObject->getNextObject($UserId);
				
				/*if ($CurrentObject->getType() == 'Coaching') {
					$Coaching = $CurrentObject;
					$CurrentObject = $this->handleCoaching($CurrentObject, $UserId);
					
					if (is_null($CurrentObject)) break;
				}*/
			}
			
			$this->dumpObject($CurrentObject);
			
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
			
			/*if (!is_null($CurrentObject) && $CurrentObject->getType() == 'Coaching') {
				//break;
			}*/
		}
		
		$this->printLine("</objectsequence>");
		$this->getOutputBuffer()->flush();
	}
	
	/*protected function handleCoaching($Coaching) {
		$FirstObject = $Coaching->getFirstObject();
		
		if (!is_null($FirstObject) && $FirstObject->getType() == 'Coaching') return $this->handleCoaching($FirstObject, $this->getUser()->getId());
		else return $FirstObject;
	}*/
	
	protected function dumpObject($Object) {
		/*if ($Object->getType() == 'Coaching') {
			return;
		}*/
		
		$Object->dump();
	}
}
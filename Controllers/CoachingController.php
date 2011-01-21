<?php

class Coaching extends Controller {
	protected function getStartObject($CoachingId) {
		return CoachingFinder::find($CoachingId)->getFirstObject();
	}
	
	public function dumpCoachingPath($key, $UserId) {
		$Coaching = CoachingFinder::findByKey($key);
		
		$this->print("<objectsequence>");
		$CurrentObject = $this->getStartObject($Coaching->getId());
		
		if ($CurrentObject->getObjectName() == 'Coaching') {
			$Coaching = $CurrentObject;
		} else if (($Coaching = $CurrentObject->getCoaching($UserId))) {
			$Coaching = $Coaching;
		} else {
			$Coaching = NULL;
		}
		
		if (!is_null($Coaching)) {
			$title = $Coaching->getTitle();
			$description = $Coaching->getDescription();
		} else {
			$title = $this->lang->line('gapFillerTitle');
			$description = $this->lang->line('gapFillerDescription');
		}
		
		$this->print("\t<title>%s</title>", $title);
		$this->print("\t<description>%s</description>", $description);
		
		while (!is_null($CurrentObject)) {
			$coachingLock = NULL;
			if ($coachingLock = $this->session->userdata('coachingLock') && isset($coachingLock[$Coaching->getId()])) {
				$this->dumpObject(ObjectFinder::find($coachingLock[$Coaching->getId()]['ObjectId']));
				break;
			} else if ($CurrentObject->getObjectName() == 'Coaching') {
				$Coaching = $CurrentObject;
				$CurrentObject = $this->handleCoaching($CurrentObject, $UserId);
				
				if (is_null($CurrentObject)) break;
			}
			
			if (($CurrentObject->getObjectName() == 'SignIn' || $CurrentObject->getObjectName() == 'Payment') && isSignedIn()) {
				$CurrentObject = $CurrentObject->getNextObject($UserId);
				
				if ($CurrentObject->getObjectName() == 'Coaching') {
					$Coaching = $CurrentObject;
					$CurrentObject = $this->handleCoaching($CurrentObject, $UserId);
					
					if (is_null($CurrentObject)) break;
				}
			}
			
			$this->dumpObject($CurrentObject);
			
			if ((($CurrentObject->getObjectName() == 'SignIn' || $CurrentObject->getObjectName() == 'Payment') && !isSignedIn())) {
				$coachingLock = $this->session->userdata('coachingLock');
				$newCoachingLock = array(
					$Coaching->getId() => array(
						'ObjectId' => $CurrentObject->getId(),
						'ObjectType' => $CurrentObject->getObjectName()
					)
				);
				$this->session->set_userdata('coachingLock', is_array($coachingLock) ? array_merge($coachingLock, $newCoachingLock) : $newCoachingLock);
				
				break;
			} else if ($CurrentObject->getObjectName() == 'Questionnaire' && $CurrentObject->isActivated($UserId)) {
				break;
			}
			
			if ($CurrentObject->getObjectName() == 'Interrupt') {
				if ($interruptText = $CurrentObject->getText()) $this->session->set_userdata('interruptText', $interruptText);
				break;
			}
			
			$Ancestor = $CurrentObject;
			$CurrentObject = $Ancestor->getNextObject($UserId);
			
			if (!is_null($CurrentObject) && $CurrentObject->getObjectName() == 'Coaching') {
				break;
			}
		}
		
		$this->print("</objectsequence>");
		$this->getOutputBuffer()->flush();
	}
	
	protected function handleCoaching($Coaching, $UserId) {
		$FirstObject = $Coaching->getFirstObject();
		
		if (!is_null($FirstObject) && $FirstObject->getObjectName() == 'Coaching') return $this->handleCoaching($FirstObject, $UserId);
		else return $FirstObject;
	}
	
	protected function dumpObject($Object) {
		if ($Object->getObjectName() == 'Coaching') {
			return;
		}
		
		$Object->dump();
	}
}
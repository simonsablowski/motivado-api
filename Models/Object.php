<?php

class Object extends Model {
	protected $tableName = 'object';
	protected $fields = array(
		'id',
		'CoachingId',
		'type',
		'title',
		'data',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'CoachingId',
		'type',
		'title',
	);
	
	protected $Coaching = NULL;
	protected $NextObject = NULL;
	protected $NextObjects = NULL;
	protected $Video = NULL;
	
	protected function loadCoaching($UserId = NULL) {
		try {
			$ObjectSequence = ObjectSequenceFinder::findFirst(array(
				'RightId' => $this->getId()
			));
			if ($ObjectSequence->getCoachingId() == 0) {
				return;
			}
		} catch (Exception $Error) {
			return;
		}
		
		if (!is_null($UserId)) {
			$UsersCoachings = UsersCoachingFinder::findAll(array(
				'UserId' => $UserId
			), array('modified DESC'));
			
			if (!$UsersCoachings) {
				return;
			}
			
			foreach ($UsersCoachings as $UsersCoaching) {
				$Coaching = CoachingFinder::find($UsersCoaching->getObjectId());
				if ($Coaching->getReferenceId() == $ObjectSequence->getCoachingId()) {
					$this->Coaching = $Coaching;
					break;
				}
			}
		} else {
			$this->Coaching = CoachingFinder::find($ObjectSequence->getCoachingId());
		}
	}
	
	public function getCoaching($UserId = NULL) {
		if (is_null($this->Coaching)) $this->loadCoaching($UserId);
		return $this->Coaching;
	}
	
	protected function loadNextObjects($UserId = NULL, $condition = array()) {
		$this->NextObjects = ObjectSequenceFinder::findNextObjects($this, $condition);
	}
	
	public function getNextObjects($UserId = NULL, $condition = array()) {
		if (is_null($this->NextObjects)) $this->loadNextObjects($UserId, $condition);
		return $this->NextObjects;
	}
	
	protected function loadNextObject($UserId = NULL, $condition = array()) {
		$NextObjects = $this->getNextObjects($UserId, $condition);
		
		if (count($NextObjects) == 1) {
			$this->NextObject = pos($NextObjects);
		} else if (!is_null($UserId)) {
			$this->loadNextObjectSuitableToCharacterTraits($UserId);
		}
		
		if (!$NextObjects || (!is_null($this->NextObject) && $this->NextObject->getType() == 'End')) $this->loadCoachingsNextObject($UserId);
	}
	
	protected function loadNextObjectSuitableToCharacterTraits($UserId) {
		$User = UserFinder::find($UserId, array('status' => NULL));
		$NextObjects = $this->getNextObjects($UserId);
		$IndependentObjects = array();
		
		foreach ($NextObjects as $NextObject) {
			$ObjectCharacterTraitDependencies = ObjectCharacterTraitDependencyFinder::findAll(array(
				'ObjectId' => $NextObject->getId()
			));
			
			if (!$ObjectCharacterTraitDependencies) {
				if ($this->isIndependent($NextObject)) {
					$IndependentObjects[] = $NextObject;
				}
				continue;
			}
			
			$suitableCharacterTraits = array();
			foreach ($ObjectCharacterTraitDependencies as $ObjectCharacterTraitDependency) {
				$cid = $ObjectCharacterTraitDependency->getCharacterTraitId();
				
				if (!$User->hasCharacterTrait($cid)) {
					continue 2;
				}
				
				$value = $ObjectCharacterTraitDependency->getValue();
				$suitableCharacterTraits[$cid] = $value;
			}
			
			if ($suitableCharacterTraits && $User->hasSuitableCharacterTraits($suitableCharacterTraits)) {
				$this->NextObject = $NextObject;
				return;
			}
		}
		
		$this->NextObject = $IndependentObjects ? pos($IndependentObjects) : NULL;
	}
	
	protected function isIndependent($Ancestor = NULL) {
		try {
			ObjectCharacterTraitDependencyFinder::findFirst(array(
				'ObjectId' => $this->getId()
			));
			return FALSE;
		} catch (Exception $Error) {
			return TRUE;
		}
	}
	
	protected function loadIndependentNextObject($UserId) {
		$NextObjects = $this->getNextObjects($UserId);
		if (!$NextObjects) {
			return;
		}
		
		foreach ($NextObjects as $NextObject) {
			if ($NextObject->isIndependent($this)) {
				$this->NextObject = $NextObject;
				break;
			}
		}
	}
	
	protected function loadCoachingsNextObject($UserId) {
		if ($Coaching = $this->getCoaching($UserId)) {
			$this->NextObject = $Coaching->getNextObject($UserId);
		} else {
			$this->NextObject = NULL;
		}
	}
	
	public function getNextObject($UserId = NULL, $condition = array()) {
		if (is_null($this->NextObject)) $this->loadNextObject($UserId, $condition);
		return $this->NextObject;
	}
	
	protected function loadVideo() {
		try {
			$ObjectsVideo = ObjectsVideoFinder::findFirst(array(
				'ObjectId' => $this->getId()
			));
			$this->Video = $ObjectsVideo->getVideo();
		} catch (Exception $Error) {
			$this->Video = NULL;
		}
	}
	
	public function getDumpsHiddenFields() {
		return array('id', 'CoachingId', 'status', 'created', 'modified');
	}
	
	public function dumpHeader($depth) {
		$this->print("%s<object id=\"%s\" type=\"%s\">", array(str_repeat("\t", $depth), $this->getId(), $this->getType()));
	}
	
	public function cleanDumpsProperty($value) {
		$value = preg_replace('/&(.+);/', '', $value);
		$value = preg_replace('/<br([\s\/]*)>/', "\n", $value);
		$value = preg_replace('/<(?:[^"\']+?|.+?(?:"|\').*?(?:"|\')?.*?)*?>/', '$1', $value);
		$value = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $value);
		return $value;
	}
	
	public function dumpProperty($field, $value, $depth) {
		$field = strtolower($field);
		$value = $this->cleanDumpsProperty($value);
		$this->print("%s" . ($value != '' ? "<%s>%s</%s>" : "<%s />"), (str_repeat("\t", $depth + 1), $field, $value, $field));
	}
	
	public function dumpFooter($depth) {
		$this->print("%s</object>", str_repeat("\t", $depth));
	}
	
	//TODO: also dump unserialized data field
	public function dump($depth = 1) {
		$this->dumpHeader($depth);
		foreach ($this->getData() as $field => $value) {
			if (in_array($field, $this->getDumpsHiddenFields())) continue;
			$this->dumpProperty($field, $value, $depth);
		}
		if ($this->hasVideo()) $dump .= $this->getVideo()->dump($depth + 1, 'objectsvideo');
		$this->dumpFooter($depth);
	}
}
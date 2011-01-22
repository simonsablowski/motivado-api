<?php

class Object extends Model {
	protected $fields = array(
		'id',
		'CoachingId',
		'type',
		'title',
		//'data',
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
	
	protected function loadNextObjects($UserId = NULL, $condition = array()) {
		$this->setNextObjects(ObjectSequence::findNextObjects($this, $condition));
	}
	
	public function getNextObjects($UserId = NULL, $condition = array()) {
		if (is_null($this->NextObjects)) $this->loadNextObjects($UserId, $condition);
		return $this->NextObjects;
	}
	
	protected function loadNextObject($UserId = NULL, $condition = array()) {
		$NextObjects = $this->getNextObjects($UserId, $condition);
		
		if (count($NextObjects) == 1) {
			$this->setNextObject(pos($NextObjects));
		}/* else if (!is_null($UserId)) {
			$this->loadNextObjectSuitableToCharacterTraits($UserId);
		}*/
	}
	
	/*protected function loadNextObjectSuitableToCharacterTraits($UserId) {
		$User = User::find($UserId, array('status' => NULL));
		$NextObjects = $this->getNextObjects($UserId);
		$IndependentObjects = array();
		
		foreach ($NextObjects as $NextObject) {
			$ObjectCharacterTraitDependencies = ObjectCharacterTraitDependency::findAll(array(
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
				$this->setNextObject($NextObject);
				return;
			}
		}
		
		$this->setNextObject($IndependentObjects ? pos($IndependentObjects) : NULL);
	}
	
	protected function isIndependent($Ancestor = NULL) {
		try {
			ObjectCharacterTraitDependency::findFirst(array(
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
				$this->setNextObject($NextObject);
				break;
			}
		}
	}*/
	
	public function getNextObject($UserId = NULL, $condition = array()) {
		if (is_null($this->NextObject)) $this->loadNextObject($UserId, $condition);
		return $this->NextObject;
	}
	
	public function getDumpsHiddenFields() {
		return array('id', 'CoachingId', 'type', 'status', 'created', 'modified');
	}
	
	public function dumpHeader($depth) {
		$this->printLine("%s<%s id=\"%d\"%s>", array(str_repeat("\t", $depth), strtolower($this->getClassName()), $this->getId(), $this->isField('type') ? sprintf(" type=\"%s\"", $this->getType()) : ''));
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
		$this->printLine("%s" . ($value != '' ? "<%s>%s</%s>" : "<%s />"), array(str_repeat("\t", $depth + 1), $field, $value, $field));
	}
	
	public function dumpFooter($depth) {
		$this->printLine("%s</%s>", array(str_repeat("\t", $depth), strtolower($this->getClassName())));
	}
	
	//TODO: also dump nested Video as well as unserialized data field (needs other name)
	public function dump($depth = 1) {
		$this->dumpHeader($depth);
		foreach ($this->getData() as $field => $value) {
			if (in_array($field, $this->getDumpsHiddenFields())) continue;
			$this->dumpProperty($field, $value, $depth);
		}
		$this->dumpFooter($depth);
	}
}
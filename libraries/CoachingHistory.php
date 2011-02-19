<?php

class CoachingHistory extends Application {
	protected $use = TRUE;
	
	public function __construct($use = TRUE) {
		$this->setUse($use);
	}
	
	public function getData($Coaching = NULL) {
		if (!$this->getUse()) return NULL;
		
		$data = $this->getSession()->getData('CoachingHistory');
		if (is_null($Coaching)) {
			return $data;
		} else if ($Coaching instanceof Coaching && is_array($data) && isset($data[$Coaching->getId()])) {
			return $data[$Coaching->getId()];
		} else {
			return array();
		}
	}
	
	public function extend(Coaching $Coaching, Object $Object) {
		if (!$this->getUse()) return NULL;
		
		$data = $this->getData();
		$data[$Coaching->getId()][] = $Object;
		return $this->getSession()->setData('CoachingHistory', $data);
	}
	
	public function clear() {
		if (!$this->getUse()) return NULL;
		
		return $this->getSession()->setData('CoachingHistory', NULL);
	}
	
	public function getCurrentObject(Coaching $Coaching) {
		$data = $this->getData($Coaching);
		return is_array($data) ? end($data) : NULL;
	}
}
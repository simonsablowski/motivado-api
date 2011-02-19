<?php

class CoachingHistory extends Application {
	public function __construct() {
		
	}
	
	public function getData($Coaching = NULL) {
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
		$data = $this->getData();
		$data[$Coaching->getId()][] = $Object;
		return $this->getSession()->setData('CoachingHistory', $data);
	}
	
	public function clear() {
		return $this->getSession()->setData('CoachingHistory', NULL);
	}
	
	public function getCurrentObject(Coaching $Coaching) {
		$data = $this->getData($Coaching);
		return is_array($data) ? end($data) : NULL;
	}
}
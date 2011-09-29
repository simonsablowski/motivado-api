<?php

namespace Motivado\Api;

class LoopHandler extends \Application {
	public function __construct() {
		
	}
	
	public function getData() {
		return ($data = $this->getSession()->getData('LoopHandler')) ? $data : array();
	}
	
	public function exists($element) {
		return in_array($element, $this->getData());
	}
	
	public function register($element) {
		$data = $this->getData();
		if (!$this->exists($element)) {
			$data[] = $element;
		}
		return $this->getSession()->setData('LoopHandler', $data);
	}
	
	public function clear() {
		return $this->getSession()->setData('LoopHandler', NULL);
	}
}
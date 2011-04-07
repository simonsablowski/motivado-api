<?php

class ObjectSequence extends Application {
	public function __construct(Coaching $Coaching, $Objects) {
		foreach ($Coaching->getData() as $field => $value) {
			$this->$field = $value;
		}
		$this->Objects = array();
		foreach ($Objects as $n => $Object) {
			$this->Objects[$n] = $Object;
			foreach ($Object->getData() as $field => $value) {
				$this->Objects[$n]->$field = $field != 'properties' ? $value : Json::decode($value);
			}
		}
	}
	
	public function asXml() {
		return Xml::dumpObject($this, $this->getClassName());
	}
	
	public function asJson() {
		return Json::encode($this);
	}
}
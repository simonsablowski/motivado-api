<?php

namespace Motivado\Api;

class ObjectSequence extends \Application {
	public function __construct(Coaching $Coaching, $Objects, $endReached) {
		foreach ($Coaching->getData() as $field => $value) {
			$this->$field = $value;
		}
		$this->objects = array();
		foreach ($Objects as $n => $Object) {
			$this->objects[$n] = array();
			foreach ($Object->getData() as $field => $value) {
				$this->objects[$n][$field] = $field != 'properties' ? $value : Json::decode($value);
			}
		}
		if ($endReached) {
			$this->objects[] = array(
				'end' => TRUE
			);
		}
	}
}
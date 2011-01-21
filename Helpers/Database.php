<?php

abstract class Database extends Application {
	protected $requiredFields = array();
	
	public function __construct($configuration) {
		foreach ($this->getRequiredFields() as $field) {
			if (!array_key_exists($field, $configuration)) {
				throw new Error('Required fields missing', array('array_keys($configuration)' => array_keys($configuration), '$this->getRequiredFields()' => $this->getRequiredFields()));
			}
			
			$this->configuration[$field] = $config[$field];
		}
	}
	
	abstract public function connect();
}
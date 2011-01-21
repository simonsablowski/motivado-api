<?php

class Application {
	private $Session = NULL;
	private $Request = NULL;
	private $Controller = NULL;
	protected $configuration = array();
	
	public function __call($method, $parameters) {
		preg_match_all('/(^|[A-Z]{1})([a-z]+)/', $method, $methodParts);
		if (!isset($methodParts[0][0]) || !isset($methodParts[0][1])) throw new Error('Invalid method format', $method);
		
		$operation = $methodParts[0][0];
		array_shift($methodParts[0]);
		
		$propertyCapitalized = implode('', $methodParts[0]);
		$property = strtolower(substr($propertyCapitalized, 0, 1)) . substr($propertyCapitalized, 1);
		
		$propertyExists = FALSE;
		
		if (property_exists($this, $property)) {
			$propertyExists = TRUE;
		} else if (property_exists($this, $propertyCapitalized)) {
			$propertyExists = TRUE;
			$property = $propertyCapitalized;
		}
		
		if (!$propertyExists) throw new Error('Undeclared property', $property);
		
		switch ($operation) {
			case 'get':
				return $this->$property;
			case 'is':
				return $this->$property == 'yes';
			case 'set':
				$this->$property = $parameters[0];
				return;
		}
	}
	
	public function __construct($configuration, $RequestQuery) {
		$this->setConfiguration($configuration);
		$this->analyzeRequest($RequestQuery);
	}
	
	final public function run() {
		$this->setup();
		$this->getController()->performAction($this->getRequest()->getAction(), $this->getRequest()->getParameters());
	}
	
	final private function setup() {
		$this->initializeSession();
		$this->initializeDatabase();
		$this->initializeController();
	}
	
	final private function analyzeRequest($RequestQuery) {
		$this->setRequest(new Request($RequestQuery));
		$this->getRequest()->setConfiguration($this->getConfiguration('Request'));
		$this->getRequest()->analyze();
	}
	
	final private function initializeSession() {
		$this->setSession(new Session);
		$this->getSession()->start();
	}
	
	final private function initializeDatabase() {
		Database::initialize($this->getConfiguration('Database'));
		Database::connect();
	}
	
	final private function initializeController() {
		$ControllerName = $this->getRequest()->getController() . 'Controller';
		$this->setController(new $ControllerName);
		$this->getController()->setConfiguration($this->getConfiguration());
		$this->getController()->setSession($this->getSession());
		$this->getController()->setUser(new User(array('firstName' => 'Simon')));//TODO: dummy code
	}
	
	protected function getConfiguration($field = NULL) {
		return !is_null($field) ? (isset($this->configuration[$field]) ? $this->configuration[$field] : NULL) : $this->configuration;
	}
	
	protected function printLine($line, $arguments = array()) {
		vprintf("\n" . $line, is_array($arguments) ? $arguments : array($arguments));
	}
}
<?php

class Application {
	protected $Session = NULL;
	protected $Database = NULL;
	protected $Request = NULL;
	protected $Controller = NULL;
	protected $OutputBuffer = NULL;
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
				return self::$property;
			case 'is':
				return self::$property == 'yes';
			case 'set':
				self::$property = $parameters[0];
				return;
		}
	}
	
	public function __construct($configuration, $requestQuery) {
		$this->setConfiguration($configuration);
		$this->analyzeRequest($requestQuery);
	}
	
	final public function run() {
		$this->setup();
		$this->getController()->performAction($this->getRequest()->getAction());
	}
	
	final private function setup() {
		error_reporting(E_ALL);
		
		$this->startSession();
		$this->connectToDatabase();
		$this->initializeController();
		$this->initializeOutputBuffer();
	}
	
	final private function analyzeRequest($requestQuery) {
		$this->setRequest(new Request($requestQuery));
		$this->getRequest()->analyze();
	}
	
	final private function startSession() {
		$this->setSession(new Session);
		$this->getSession()->start();
	}
	
	final private function connectToDatabase() {
		$this->setDatabase(new Database($this->getConfiguration('Database')));
		$this->getDatabase()->connect();
	}
	
	final private function initializeController() {
		$controllerName = $this->getRequest()->getController() . 'Controller';
		$this->setController(new $controllerName);
	}
	
	final private function initializeOutputBuffer() {
		$this->setOutputBuffer(new OutputBuffer);
	}
	
	protected getConfiguration($field = NULL) {
		return !is_null($field) ? (isset($this->configuration[$field]) ? $this->configuration[$field] : NULL) : $this->configuration;
	}
	
	protected function print($line, $arguments = array()) {
		vprintf("\n" . $line, is_array($arguments) ? $arguments : array($arguments));
	}
}
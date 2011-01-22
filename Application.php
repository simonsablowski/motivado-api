<?php

class Application {
	protected $configuration = array();
	private $Request = NULL;
	private $OutputBuffer = NULL;
	private $Session = NULL;
	private $Controller = NULL;
	
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
		header('Content-Type: text/xml; charset=utf-8');
		printf("<?xml version=\"1.0\" encoding=\"utf-8\"?>");
		
		try {
			$this->setOutputBuffer(new OutputBuffer);
			$this->getOutputBuffer()->start();
			
			$this->setup();
			$this->getController()->performAction($this->getRequest()->getAction(), $this->getRequest()->getParameters());
			
			$this->getOutputBuffer()->flush();
		} catch (Exception $Error) {
			$this->getOutputBuffer()->clean();
			
			$this->printLine("<response>");
			$this->printLine("\t<status>Failure</status>");
			$Error->dump();
			$this->printLine("</response>");
			
			$this->getOutputBuffer()->flush();
		}
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
		if (!class_exists($ControllerName)) throw new Error('Invalid controller', $ControllerName);
		
		$this->setController(new $ControllerName);
		$this->getController()->setConfiguration($this->getConfiguration());
		$this->getController()->setSession($this->getSession());
		$this->getController()->setUser(new User(array('id' => 1, 'firstName' => 'Simon')));//TODO: dummy code
	}
	
	protected function getConfiguration($field = NULL) {
		return !is_null($field) ? (isset($this->configuration[$field]) ? $this->configuration[$field] : NULL) : $this->configuration;
	}
	
	protected function printLine($line, $arguments = array()) {
		vprintf("\n" . $line, is_array($arguments) ? $arguments : array($arguments));
	}
}
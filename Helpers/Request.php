<?php

class Request extends Application {
	protected $string = NULL;
	protected $controller = NULL;
	protected $action = NULL;
	protected $parameters = NULL;
	
	public function __construct($string) {
		$this->setString($string);
	}
	
	public function analyze() {
		$segments = explode('/', $this->getString());
		list($controller, $action) = $segments;
		array_shift($segments);
		$parameters = array_shift($segments);
		
		$this->setController($controller);
		$this->setAction($action);
		$this->setParameters($parameters);
	}
}
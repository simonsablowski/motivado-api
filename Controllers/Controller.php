<?php

abstract class Controller extends Application {
	protected $User = NULL;
	
	public function __construct() {
		header('Content-Type: text/xml; charset=utf-8');
		printf("<?xml version=\"1.0\" encoding=\"utf-8\"?>");
	}
	
	protected function performAction($actionName) {
		try {
			$this->$actionName();
			
			$this->getOutputBuffer()->flush();
		} catch (Exception $Error) {
			$this->getOutputBuffer()->clear();
			
			$this->print("<response>");
			$this->print("\t<status>Failure</status>");
			$Error->dump();
			$this->print("</response>");
			
			$this->getOutputBuffer()->flush();
		}
	}
}

<?php

abstract class Controller extends Application {
	protected $Session = NULL;
	protected $OutputBuffer = NULL;
	protected $User = NULL;
	
	public function __construct() {
		header('Content-Type: text/xml; charset=utf-8');
		printf("<?xml version=\"1.0\" encoding=\"utf-8\"?>");
	}
	
	protected function performAction($actionName, $parameters) {
		try {
			$this->setOutputBuffer(new OutputBuffer);
			$this->getOutputBuffer()->start();
			
			call_user_func_array(array($this, $actionName), $parameters);
			
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
}

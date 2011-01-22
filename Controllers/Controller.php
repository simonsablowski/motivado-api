<?php

abstract class Controller extends Application {
	protected $Session = NULL;
	protected $User = NULL;
	
	public function __construct() {
		$this->setTemporaryUser();
	}
	
	protected function setTemporaryUser() {
		$this->setUser(new TemporaryUser);
	}
	
	protected function performAction($actionName, $parameters) {
		call_user_func_array(array($this, $actionName), $parameters);
	}
}
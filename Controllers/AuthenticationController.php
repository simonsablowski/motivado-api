<?php

class AuthenticationController extends Controller {
	public function connect() {
		if ($User = Session::getData('User')) {
			$this->setUser($User);
		}
		
		$this->printLine("<authentication>");
		$this->getUser()->dump();
		$this->printLine("</authentication>");
	}
}
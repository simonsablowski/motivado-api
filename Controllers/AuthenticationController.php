<?php

class AuthenticationController extends Controller {
	public function index() {
		if ($User = Session::getData('User')) {
			$this->setUser($User);
		}
		
		$this->printLine("<authentication>");
		$this->getUser()->dump();
		$this->printLine("</authentication>");
	}
	
	public function signIn($eMailAddress, $password) {
		if ($User = User::findByEMailAddressAndPassword(array($eMailAddress, $password))) {
			Session::setData('User', $User);
		}
		
		$this->index();
	}
	
	public function signOut() {
		Session::setData('User', NULL);
		$this->setUser(new TemporaryUser);
		
		$this->index();
	}
}
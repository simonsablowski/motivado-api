<?php

class AuthenticationController extends Controller {
	public function index() {
		if ($User = $this->getSession()->getData('User')) {
			$this->setUser($User);
		}
		
		$this->displayView('Authentication.index.xml', array(
			'User' => $this->getUser()
		));
	}
	
	public function signIn($eMailAddress, $password) {
		if ($User = User::findByEMailAddressAndPassword(array($eMailAddress, $password))) {
			$this->getSession()->setData('User', $User);
		}
		
		$this->index();
	}
	
	public function signOut() {
		$this->getSession()->setData('User', NULL);
		$this->setTemporaryUser();
		
		$this->index();
	}
}
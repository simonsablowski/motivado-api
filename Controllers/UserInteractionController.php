<?php

abstract class UserInteractionController extends Controller {
	protected $User = NULL;
	
	protected function updateUser() {
		if ($User = $this->getSession()->getData('User')) {
			return $this->setUser($User);
		} else {
			return $this->setTemporaryUser();
		}
	}
	
	protected function setTemporaryUser() {
		return $this->setUser(new TemporaryUser);
	}
	
	protected function isSignedIn() {
		return !$this->getUser()->isTemporary();
	}
}
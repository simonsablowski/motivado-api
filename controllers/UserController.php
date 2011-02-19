<?php

class UserController extends Controller {
	public function showInformation($id) {
		$this->displayView('User.showInformation.php', array(
			'User' => User::find($id)
		));
	}
}
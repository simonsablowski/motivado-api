<?php

class Api extends Application {
	protected $restrictAccess = TRUE;
	
	protected function setupController() {
		parent::setupController();
		$this->getController()->setRestrictAccess($this->getRestrictAccess());
	}
	
	public function query($query) {
		$this->run(sprintf('Coaching/query/%s', $query));
	}
}
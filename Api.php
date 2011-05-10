<?php

class Api extends Application {
	protected $restrictAccess = TRUE;
	
	protected function setupController() {
		parent::setupController();
	}
	
	public function query($query) {
		if (empty($query)) {
			$configurationRequest = $this->getConfiguration('Request');
			if (isset($configurationRequest['defaultQuery'])) {
				$query = $configurationRequest['defaultQuery'];
			} else {
				throw new FatalError('No default query configured');
			}
		}
		$this->run(sprintf('Coaching/query/%s', $query));
	}
}

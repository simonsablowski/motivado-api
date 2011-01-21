<?php

class MySqlDatabase extends Database {
	protected $requiredFields = array(
		'host',
		'name',
		'user',
		'password'
	);
	protected $resource = NULL;
	
	public function connect() {
		$this->setResource(mysql_connect($this->getConfig('host'), $this->getConfig('user'), $this->getConfig('password')));
		mysql_select_db($this->getConfig('name'), $this->getResource());
	}
}
<?php

class XmlDatabase extends Database {
	protected $requiredFields = array(
		'type',
		'file'
	);
	protected $link = NULL;
	
	public function connect() {
		$this->setLink(fread($this->getConfiguration('file')));
	}
}
<?php

class Error extends Exception {
	protected $type = NULL;
	protected $details = NULL;
	
	public function __construct($message, $details = NULL, $type = 'Warning') {
		$this->setMessage($message);
		$this->setDetails($details);
		$this->setType($type);
	}
	
	public function getDetails() {
		return $this->details;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setMessage($value) {
		return $this->message = $value;
	}
	
	public function setDetails($value) {
		return $this->details = $value;
	}
	
	public function setType($value) {
		return $this->type = $value;
	}
	
	public function __toString() {
		$string = '';
		$string .= sprintf("\n\t<errortype>%s</errortype>", $this->getType());
		$string .= sprintf("\n\t<errorcode>%s</errorcode>", $this->getCode());
		$string .= sprintf("\n\t<errormessage>%s</errormessage>", $this->getMessage());
		return $string;
	}
	
	public function dump() {
		print $this->__toString();
	}
}
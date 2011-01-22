<?php

class Error extends Exception {
	protected $type = 'Warning';
	protected $details = NULL;
	
	public function __construct($message, $details = NULL) {
		$this->setMessage($message);
		$this->setDetails($details);
	}
	
	public function getDetails() {
		return $this->details;
	}
	
	public function getType() {
		return $this->type;
	}
	
	protected function setMessage($value) {
		return $this->message = $value;
	}
	
	protected function setDetails($value) {
		return $this->details = $value;
	}
	
	protected function format($variable) {
		ob_start();
		var_dump($variable);
		$formatted = trim(ob_get_contents());
		ob_end_clean();
		return $formatted;
	}
	
	public function __toString() {
		$string = '';
		$string .= sprintf("\n\t<errortype>%s</errortype>", $this->getType());
		$string .= sprintf("\n\t<errorcode>%s</errorcode>", $this->getCode());
		$string .= sprintf("\n\t<errormessage>%s</errormessage>", $this->getMessage());
		// $string .= sprintf("\n\t<errordetails>%s</errordetails>", $this->format($this->getDetails()));
		// $string .= sprintf("\n%s", $this->getTraceAsString());
		return $string;
	}
	
	public function dump() {
		print $this->__toString();
	}
}
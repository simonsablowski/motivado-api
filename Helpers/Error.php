<?php

class Error extends Exception {
	protected $type = NULL;
	protected $code = NULL;
	
	public function getType() {
		return $this->type;
	}
	
	public function getCode() {
		return $this->code;
	}
	
	public function __toString() {
		printf("\t<errortype>%s</errortype>", $this->getType());
		printf("\t<errorcode>%s</errorcode>", $this->getCode());
	}
	
	public function dump() {
		print $this->__toString();
	}
}
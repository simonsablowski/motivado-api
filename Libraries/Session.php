<?php

class Session extends Application {
	public function __construct() {
		
	}
	
	public function start() {
		session_start();
	}
	
	public function getId() {
		return session_id();
	}
	
	public function setData() {
		if (func_num_args() == 2) {
			$_SESSION[func_get_arg(0)] = func_get_arg(1);
		} else if (func_num_args() == 1) {
			$data = func_get_arg(0);
			$_SESSION = array_merge($_SESSION, $data);
		}
	}
	
	public function destroy() {
		session_destroy();
	}
}
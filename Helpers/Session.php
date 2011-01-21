<?php

class Session extends Application {
	public function start() {
		session_start();
	}
	
	public function getId() {
		return session_id();
	}
	
	public function destroy() {
		session_destroy();
	}
}
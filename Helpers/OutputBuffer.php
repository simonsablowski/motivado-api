<?php

class OutputBuffer extends Application {
	public function start() {
		ob_start();
	}
	
	public function clean() {
		ob_end_clean();
	}
	
	public function flush() {
		ob_end_flush();
	}
}
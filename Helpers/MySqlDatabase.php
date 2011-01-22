<?php

class MySqlDatabase extends SqlDatabase {
	protected static function connectToHost($host, $user, $password) {
		return mysql_connect($host, $user, $password);
	}
	
	protected static function selectDatabase($name, $link) {
		return mysql_select_db($name, $link);
	}
	
	public static function query($statement) {
		return mysql_query($statement);
	}
	
	public static function fetch($result) {
		return mysql_fetch_assoc($result);
	}
	
	public static function count($result) {
		return mysql_num_rows($result);
	}
	
	public static function escape($value) {
		return mysql_real_escape_string($value);
	}
}
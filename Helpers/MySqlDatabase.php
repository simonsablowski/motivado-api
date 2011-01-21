<?php

class MySqlDatabase extends SqlDatabase {
	public static function connect() {
		self::setLink(mysql_connect(self::getConfiguration('host'), self::getConfiguration('user'), self::getConfiguration('password')));
		mysql_select_db(self::getConfiguration('name'), self::getLink());
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
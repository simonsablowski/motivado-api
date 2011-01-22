<?php

class MySqlDatabase extends SqlDatabase {
	public static function connect() {
		if (!self::setLink(mysql_connect(self::getConfiguration('host'), self::getConfiguration('user'), self::getConfiguration('password')))) {
			throw new Error('Cannot connect to database host', self::getConfiguration('host'));
		}
		
		if (!mysql_select_db(self::getConfiguration('name'), self::getLink())) {
			throw new Error('Cannot select database', self::getConfiguration('name'));
		}
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
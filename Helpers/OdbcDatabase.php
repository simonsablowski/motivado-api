<?php

abstract class OdbcDatabase extends SqlDatabase {
	public static function query($statement) {
		return odbc_exec(self::getLink(), $statement);
	}
	
	public static function fetch($result) {
		return odbc_fetch_row($result);
	}
	
	public static function count($result) {
		return odbc_num_rows($result);
	}
	
	public static function escape($value) {
		return addslashes($value);
	}
}
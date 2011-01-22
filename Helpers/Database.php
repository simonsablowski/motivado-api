<?php

class Database {
	protected static $configuration = array();
	protected static $className = NULL;
	protected static $link = NULL;
	protected static $requiredFields = array();
	
	final public static function initialize($configuration) {
		self::setClassName($configuration['type'] . 'Database');
		
		foreach (self::getRequiredFields() as $field) {
			if (!array_key_exists($field, $configuration)) {
				throw new FatalError('Required fields missing', array('Given fields' => array_keys($configuration), 'Required fields()' => self::getRequiredFields()));
			}
			
			self::$configuration[$field] = $configuration[$field];
		}
	}
	
	final public static function getConfiguration($field = NULL) {
		return !is_null($field) ? (isset(self::$configuration[$field]) ? self::$configuration[$field] : NULL) : self::$configuration;
	}
	
	final protected static function getClassName() {
		return self::$className;
	}
	
	final protected static function setClassName($className) {
		return self::$className = $className;
	}
	
	final protected static function getLink() {
		return self::$link;
	}
	
	final protected static function setLink($link) {
		return self::$link = $link;
	}
	
	protected static function getRequiredFields() {
		return call_user_func_array(array(self::getClassName(), 'getRequiredFields'), array());
	}
	
	public static function connect() {
		return call_user_func_array(array(self::getClassName(), 'connect'), array());
	}
	
	public static function query($statement) {
		return call_user_func_array(array(self::getClassName(), 'query'), array($statement));
	}
	
	public static function fetch($result) {
		return call_user_func_array(array(self::getClassName(), 'fetch'), array($result));
	}
	
	public static function count($result) {
		return call_user_func_array(array(self::getClassName(), 'count'), array($result));
	}
	
	public static function escape($value) {
		return call_user_func_array(array(self::getClassName(), 'escape'), array($value));
	}
	
	public static function select() {
		return call_user_func_array(array(self::getClassName(), 'select'), func_get_args());
	}
	
	public static function insert() {
		return call_user_func_array(array(self::getClassName(), 'insert'), func_get_args());
	}
	
	public static function update() {
		return call_user_func_array(array(self::getClassName(), 'update'), func_get_args());
	}
	
	public static function delete() {
		return call_user_func_array(array(self::getClassName(), 'delete'), func_get_args());
	}
}
<?php

abstract class Finder extends Application {
	protected static $primaryKey = 'id';
	protected static $defaultCondition = array('status' => 'active');
	protected static $defaultSorting = array('created');
	
	public static function __callStatic($method, $parameters) {
		list($operation, , , , $methodParts) = self::resolveMethod(get_called_class(), $method);
		if ($operation != 'find') return parent::__callStatic($method, $parameters);
		
		array_shift($methodParts[0]);
		$fieldNames = implode('', $methodParts[0]);
		
		$fields = explode('And', implode('', $methodParts[0]));
		foreach ($fields as $n => $field) {
			$fields[$n] = strtolower(substr($field, 0, 1)) . substr($field, 1);
		}
		
		$values = $parameters;
		$condition = NULL;
		if ((!is_array(pos($values)) && count($values) == count($fields) + 1)
				|| (is_array(pos($values) && count($values) == 2))) {
			$condition = end($values);
			array_pop($values);
		}
		if (is_array(pos($values)) && count($values) == 1) $values = pos($values);
		
		return self::findBy($fields, $values, $condition);
	}
	
	public static function getModelName() {
		return strstr(get_called_class(), 'Finder', TRUE);
	}
	
	public static function getTableName() {
		return strtolower(self::getModelName());
	}
	
	protected static function resolveCondition($condition) {
		if (is_null($condition)) return self::getDefaultCondition();
		else return array_merge(self::getDefaultCondition(), $condition);
	}
	
	public static function findAll($condition = NULL, $sorting = NULL, $limit = NULL) {
		$Objects = array();
		$result = Database::select(self::getTableName(), self::resolveCondition($condition), $limit, !is_null($sorting) ? $sorting : self::getDefaultSorting());
		while ($row = Database::fetch($result)) {
			$modelName = self::getModelName();
			$Objects[] = new $modelName($row);
		}
		return $Objects;
	}
	
	public static function countAll($condition = NULL) {
		$result = Database::select(self::getTableName(), self::resolveCondition($condition));
		return Database::count($result);
	}
	
	public static function findFirst($condition = NULL, $sorting = NULL) {
		$Objects = self::findAll($condition, $sorting, 1);
		if ($Objects) return pos($Objects);
		else throw new Error('Record not found', $condition);
	}
	
	public static function find($primaryKeyValue, $condition = NULL) {
		$condition = array_merge(is_array($primaryKeyValue) ? $primaryKeyValue : array(self::getPrimaryKey() => $primaryKeyValue), self::resolveCondition($condition));
		$result = Database::select(self::getTableName(), $condition, 1);
		$row = Database::fetch($result);
		if (!$row) throw new Error('Record not found', array('Primary key value' => $primaryKeyValue, 'Condition' => $condition));
		
		$modelName = self::getModelName();
		return new $modelName($row);
	}
	
	public static function findBy($fields, $values, $condition = NULL) {
		$condition = array_merge(array_combine($fields, $values), self::resolveCondition($condition));
		$result = Database::select(self::getTableName(), $condition, 1);
		$row = Database::fetch($result);
		if (!$row) throw new Error('Record not found', array('Fields' => $fields, 'Values' => $values, 'Condition' => $condition));
		
		$modelName = self::getModelName();
		return new $modelName($row);
	}
}
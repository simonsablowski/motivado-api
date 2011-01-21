<?php

abstract class Finder extends Application {
	protected static $tableName = NULL;
	protected static $primaryKey = 'id';
	protected static $defaultCondition = array('status' => 'active');
	protected static $defaultSorting = array('created');
	
	public static function __callStatic($method, $parameters) {
		preg_match_all('/(^|[A-Z]{1})([a-z]+)/', $method, $methodParts);
		if (!isset($methodParts[0][0]) || !isset($methodParts[0][1])) throw new Exception('Invalid method format', $method);
		
		$operation = $methodParts[0][0];
		array_shift($methodParts[0]);
		
		if ($operation == 'get') {
			$propertyCapitalized = implode('', $methodParts[0]);
			$property = strtolower(substr($propertyCapitalized, 0, 1)) . substr($propertyCapitalized, 1);
			
			$propertyExists = FALSE;
			
			if (property_exists(get_called_class(), $property)) {
				$propertyExists = TRUE;
			} else if (property_exists(get_called_class(), $propertyCapitalized)) {
				$propertyExists = TRUE;
				$property = $propertyCapitalized;
			}
			
			if (!$propertyExists) throw new Error('Undeclared property', $property);
			
			return self::$$property;
		}
		
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
	
	public static function findAll($condition = NULL, $sorting = NULL, $limit = NULL) {
		if (is_null($condition)) $condition = self::getDefaultCondition();
		else $condition = array_merge(self::getDefaultCondition(), $condition);
		if (is_null($sorting)) $sorting = self::getDefaultSorting();
		
		$Objects = array();
		$result = Database::select(self::getTableName(), $condition, $limit, $sorting);
		while ($row = Database::fetch($result)) {
			$modelName = self::getModelName();
			$Objects[] = new $modelName($row);
		}
		return $Objects;
	}
	
	public static function countAll($condition = NULL) {
		if (is_null($condition)) $condition = self::getDefaultCondition();
		else $condition = array_merge(self::getDefaultCondition(), $condition);
		
		$result = Database::select(self::getTableName(), $condition);
		return Database::count($result);
	}
	
	public static function findFirst($condition = NULL, $sorting = NULL) {
		$Objects = self::findAll($condition, $sorting, 1);
		if ($Objects) return $Objects[0];
		else throw new Error('Record not found', $condition);
	}
	
	public static function find($primaryKeyValue, $condition = NULL) {
		if (is_null($condition)) $condition = self::getDefaultCondition();
		else $condition = array_merge(self::getDefaultCondition(), $condition);
		if (!is_array($primaryKeyValue)) $primaryKeyValue = array(self::getPrimaryKey() => $primaryKeyValue);
		$condition = array_merge($primaryKeyValue, $condition);
		
		$result = Database::select(self::getTableName(), $condition, 1);
		$row = Database::fetch($result);
		if (!$row) throw new Error('Record not found', array('$primaryKeyValue' => $primaryKeyValue, '$condition' => $condition));
		
		$modelName = self::getModelName();
		return new $modelName($row);
	}
	
	public static function findBy($fields, $values, $condition = NULL) {
		if (is_null($condition)) $condition = self::getDefaultCondition();
		else $condition = array_merge(self::getDefaultCondition(), $condition);
		$condition = array_merge(array_combine($fields, $values), $condition);
		
		$result = Database::select(self::getTableName(), $condition, 1);echo mysql_error();
		$row = Database::fetch($result);
		if (!$row) throw new Error('Record not found', array('$fields' => $fields, '$values' => $values, '$condition' => $condition));
		
		$modelName = self::getModelName();
		return new $modelName($row);
	}
}
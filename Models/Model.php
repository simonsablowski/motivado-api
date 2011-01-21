<?php

abstract class Model extends Application {
	protected $primaryKey = 'id';
	protected $fields = array();
	protected $requiredFields = array();
	protected $data = array();
	
	public function __call($method, $parameters) {
		preg_match_all('/(^|[A-Z]{1})([a-z]+)/', $method, $methodParts);
		if (!isset($methodParts[0][0]) || !isset($methodParts[0][1])) throw new Error('Invalid method format', $method);
		
		$operation = $methodParts[0][0];
		array_shift($methodParts[0]);
		
		$propertyCapitalized = implode('', $methodParts[0]);
		$property = strtolower(substr($propertyCapitalized, 0, 1)) . substr($propertyCapitalized, 1);
		
		$propertyExists = FALSE;
		$isField = FALSE;
		$hasLoader = FALSE;
		
		if (property_exists($this, $property)) {
			$propertyExists = TRUE;
		} else if (property_exists($this, $propertyCapitalized)) {
			$propertyExists = TRUE;
			$property = $propertyCapitalized;
			
			if (method_exists($this, 'load' . $property) || property_exists($this, $property . 'Id')) {
				$hasLoader = TRUE;
			}
		}
		
		if ($this->isField($property)) {
			$isField = TRUE;
		} else if ($this->isField($propertyCapitalized)) {
			$isField = TRUE;
			$property = $propertyCapitalized;
		}
		
		switch ($operation) {
			case 'get':
				if (($isField || $propertyExists) && $hasLoader) {
					$loaderName = 'load' . $property;
					if (is_null($this->$property)) $this->$loaderName();
					return $this->$property;
				} else if ($isField) {
					return $this->getData($property);
				} else if ($propertyExists) {
					return $this->$property;
				}
			case 'is':
				if ($isField) {
					return $this->getData($property) == 'yes';
				} else if ($propertyExists) {
					return $this->$property == 'yes';
				}
			case 'has':
				if (($isField || $propertyExists) & $hasLoader) {
					$loaderName = 'load' . $property;
					if (is_null($this->$property)) $this->$loaderName();
					return is_object($this->$property);
				} else if ($isField) {
					return $this->getData($property) != FALSE;
				} else if ($propertyExists) {
					return $this->$property != FALSE;
				}
			case 'load':
				if ($isField & $hasLoader) {
					$finderName = $property . 'Finder';
					$getterName = 'get' . $property . 'Id';
					$this->$property = $finderName::find($this->$getterName());
					return;
				}
			case 'set':
				if ($isField) {
					$this->setData($property, $parameters[0]);
					return;
				} else if ($propertyExists) {
					$this->$property = $parameters[0];
					return;
				}
		}
		
		throw new Error('Undeclared property', $property);
	}
	
	public function __construct() {
		$requiredFields = $this->getRequiredFields();
		$arguments = func_get_args();
		
		if (func_num_args() == 1 && is_array(func_get_arg(0))) {
			$data = func_get_arg(0);
		} else {
			if (count($requiredFields) != count($arguments)) {
				throw new Error('Number of required fields does not match number of arguments', array('$arguments' => $arguments, '$requiredFields' => $requiredFields));
			}
			
			$data = $arguments ? array_combine($requiredFields, $arguments) : array();
		}
		
		foreach ($requiredFields as $field) {
			if (!array_key_exists($field, $data)) {
				throw new Error('Required fields missing', array('array_keys($data)' => array_keys($data), '$requiredFields' => $requiredFields));
			}
		}
		
		foreach ($data as $property => $value) {
			if (!$this->isField($property)) continue;
			$setter = 'set' . ucfirst($property);
			$this->$setter($value);
		}
	}
	
	public function getModelName() {
		return get_class($this);
	}
	
	public function getTableName() {
		return strtolower($this->getModelName());
	}
	
	protected function getPrimaryKeyValue() {
		$primaryKeyValue = array();
		if (is_string($this->getPrimaryKey())) {
			$getterName = 'get' . ucfirst($this->getPrimaryKey());
			$primaryKeyValue[$this->getPrimaryKey()] = $this->$getterName();
		} else if (is_array($this->getPrimaryKey())) {
			foreach ($this->getPrimaryKey() as $field) {
				$getterName = 'get' . ucfirst($field);
				$primaryKeyValue[$field] = $this->$getterName();
			}
		}
		return $primaryKeyValue;
	}
	
	public function isField($field) {
		return in_array($field, $this->fields);
	}
	
	public function getData($field = NULL) {
		if (is_null($field)) return $this->data;
		else if (isset($this->data[$field])) return $this->data[$field];
		else return NULL;
	}
	
	public function setData() {
		if (func_num_args() == 2) {
			$this->data[func_get_arg(0)] = func_get_arg(1);
		} else if (func_num_args() == 1) {
			$data = func_get_arg(0);
			$this->data = array_merge($this->data, $data);
		}
	}
	
	protected function prepareSaving() {
		if ($this->isField('status')) $this->setStatus('active');
		if ($this->isField('created')) $this->setData('created', 'NOW()');
	}
	
	public function save() {
		$this->prepareSaving();
		return Database::insert($this->getTableName(), $this->getData());
	}
	
	protected function prepareUpdating() {
		if ($this->isField('modified')) $this->setData('modified', 'NOW()');
	}
	
	public function update() {
		$this->prepareUpdating();
		return Database::update($this->getTableName(), $this->getData(), $this->getPrimaryKeyValue(), 1);
	}
	
	protected function prepareDeleting() {
		$this->prepareUpdating();
		if ($this->isField('status')) $this->setStatus('deleted');
	}
	
	public function delete() {
		$this->prepareDeleting();
		return Database::update($this->getTableName(), $this->getData(), $this->getPrimaryKeyValue(), 1);
	}
	
	public function saveSafely($condition = array('status' => NULL)) {
		$modelName = $this->getModelName();
		$finderName = $modelName . 'Finder';
		try {
			$Object = $finderName::find($this->getPrimaryKeyValue(), $condition);
			$Object->setData($this->getData());
			$Object->update();
		} catch (Exception $Error) {
			$Object = new $modelName($this->getData());
			$Object->save();
		}
	}
	
	public function isModified() {
		return $this->isField('modified') && $this->getModified() != '0000-00-00 00:00:00';
	}
}
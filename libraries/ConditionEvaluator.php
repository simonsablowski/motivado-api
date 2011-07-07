<?php

namespace Motivado\Api;

class ConditionEvaluator extends \Application {
	protected $CoachingConfigurator = NULL;
	protected $operators = array(
		'is' => '==',
		'not' => '!=',
		'lt' => '<',
		'le' => '<=',
		'gt' => '>',
		'ge' => '>=',
		'and' => '&&',
		'or' => '||'
	);
	const prefix = 'value_';
	
	public function __construct() {
		$this->setCoachingConfigurator(new \CoachingConfigurator);
	}
	
	protected function getVariableName($variable) {
		return self::prefix . $variable;
	}
	
	protected function secureCondition(&$condition) {
		if (preg_match('/[^\s\w()\']/i', $condition, $characters)) {
			throw new \Error('Condition contains invalid characters', $characters);
		}
		
		foreach ($this->getOperators() as $alias => $original) {
			$condition = preg_replace(sprintf('/(\s+)%s(\s+)/', $alias), sprintf('$1%s$2', $original), $condition);
		}
		
		if (!preg_match_all('/(^|\s|\()([a-z0-9]{1}\w+)([^\s\)$])?/i', $condition, $variables)) {
			throw new \Error('Condition contains no valid variables', $condition);
		} else {
			foreach ($variables[3] as $n => $element) {
				if ($element) {
					throw new \Error('Condition contains invalid element', $variables[0][$n]);
				}
			}
		}
		
		foreach ($variables[0] as $n => $part) {
			$variable = $variables[2][$n];
			$substitution = '$' . $this->getVariableName($variable);
			$condition = str_replace($part, str_replace($variable, $substitution, $part), $condition);
		}
		
		return $condition = sprintf('return %s;', $condition);
	}
	
	public function evaluate($condition) {
		try {
			foreach ($this->getCoachingConfigurator()->getValues() as $name => $item) {
				$variable = $this->getVariableName($name);
				$$variable = \Motivado\Api\Json::decode($item['value']);
			}
			
			$level = error_reporting(0);
			$this->secureCondition($condition);
			$result = (bool)eval($condition);
			error_reporting($level);
			
			return $result;
		} catch (\Error $Error) {
			return FALSE;
		}
	}
}
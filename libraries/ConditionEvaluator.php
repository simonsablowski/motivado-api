<?php

class ConditionEvaluator extends Application {
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
	
	public function __construct() {
		$this->setCoachingConfigurator(new CoachingConfigurator);
	}
	
	protected function secureCondition(&$condition) {
		if (preg_match('/[^\s\w()\']/i', $condition)) {
			throw new FatalError('Condition contains invalid characters', $condition);
		}
		
		foreach ($this->getOperators() as $alias => $original) {
			$condition = preg_replace(sprintf('/(\s+)%s(\s+)/', $alias), sprintf('$1%s$2', $original), $condition);
		}
		
		if (!preg_match_all('/(^|\s|\()([a-z]{1}\w+)/i', $condition, $variables)) {
			throw new FatalError('Condition contains no valid variables', $condition);
		}
		
		foreach ($variables[0] as $n => $part) {
			if (preg_match(sprintf('/%s([^\s\)$]+)/', $part), $condition, $element)) {
				throw new FatalError('Condition contains invalid element', $element);
			}
			
			$variable = $variables[2][$n];
			$condition = str_replace($part, str_replace($variable, '$' . $variable, $part), $condition);
		}
		
		$condition = sprintf('return %s;', $condition);
	}
	
	public function evaluate($condition) {
		extract($this->getCoachingConfigurator()->getValues());
		
		$level = error_reporting(0);
		$this->secureCondition($condition);
		$result = (bool)eval($condition);
		error_reporting($level);
		
		return $result;
	}
}
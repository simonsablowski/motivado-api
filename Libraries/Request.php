<?php

class Request extends Application {
	protected $query = NULL;
	protected $controller = NULL;
	protected $action = NULL;
	protected $parameters = NULL;
	
	public function __construct($query) {
		$this->setQuery($query);
	}
	
	protected function getAlias($query) {
		$aliasQueries = $this->getConfiguration('aliasQueries');
		return isset($aliasQueries[$query]) ? $aliasQueries[$query] : $query;
	}
	
	protected function resolveQuery() {
		$query = !is_null($this->getQuery()) ? $this->getQuery() : $this->getConfiguration('defaultQuery');
		return $this->getAlias($query);
	}
	
	public function analyze() {
		$segments = explode('/', $this->resolveQuery());
		
		if (isset($segments[0])) $this->setController($segments[0]);
		else throw new FatalError('No controller defined', $segments);
		
		if (isset($segments[1])) $this->setAction($segments[1]);
		else throw new FatalError('No action defined', $segments);
		
		$this->setParameters(array_slice($segments, 2));
	}
}
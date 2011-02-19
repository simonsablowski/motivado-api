<?php

$configuration = array();

$configuration['header'] = 'Content-Type: text/xml; charset=utf-8';

$configuration['Database'] = array(
	'type' => 'MySql',
	'host' => 'localhost',
	'name' => 'motivado_api',
	'user' => 'root',
	'password' => ''
);

$configuration['defaultQuery'] = 'coaching/beziehung';

$configuration['aliasQueries'] = array(
	'(coaching)/(\w+)' => '$1/query/$2'
);

$configuration['debugMode'] = TRUE;
//$configuration['debugMode'] = FALSE;
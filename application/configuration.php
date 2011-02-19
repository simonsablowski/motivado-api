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

$configuration['Localization'] = array(
	'language' => 'de_DE',
	'locale' => 'de_DE'
);

$configuration['Request'] = array(
	'defaultQuery' => 'coaching/beziehung',
	'aliasQueries' => array(
		'(coaching)/(\w+)' => '$1/query/$2'
	)
);

$configuration['debugMode'] = TRUE;
//$configuration['debugMode'] = FALSE;
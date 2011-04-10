<?php

$configuration = array();

// $configuration['header'] = 'Content-Type: text/xml; charset=utf-8';

$configuration['pathApplication'] = dirname(__FILE__) . '/';

$configuration['includeDirectories'] = array(
	$configuration['pathApplication'],
	'D:/Webprojekte/nacho/'
);

$configuration['Database'] = array(
	'type' => 'MySql',
	'host' => 'localhost',
	// 'name' => 'motivado_api',
	'name' => 'motivado_importer',
	'user' => 'root',
	'password' => ''
);

$configuration['Localization'] = array(
	'default' => 'de_DE',
	'de_DE' => array(
		'language' => 'de_DE',
		'locale' => 'de_DE'
	)
);

$configuration['Request'] = array(
	'defaultQuery' => 'test',
	'aliasQueries' => array()
);

$configuration['restrictAccess'] = FALSE;
// $configuration['restrictAccess'] = TRUE;

$configuration['debugMode'] = TRUE;
// $configuration['debugMode'] = FALSE;
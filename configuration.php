<?php

$configuration = array();

$configuration['header'] = 'text/json';

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

$configuration['restrictAccess'] = FALSE;
// $configuration['restrictAccess'] = TRUE;

$configuration['formatJson'] = TRUE;
// $configuration['formatJson'] = FALSE;

$configuration['debugMode'] = TRUE;
// $configuration['debugMode'] = FALSE;
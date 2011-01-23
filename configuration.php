<?php

$configuration = array(
	'header' => 'Content-Type: text/xml; charset=utf-8',
	'Database' => array(
		'type' => 'MySql',
		'host' => 'localhost',
		'name' => 'motivado_api',
		'user' => 'root',
		'password' => ''
	),
	'defaultQuery' => 'index',
	'aliasQueries' => array(
		'index' => 'Coaching/query/beziehung'
	)
);
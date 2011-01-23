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

$configuration['Request'] = array(
	'defaultController' => 'Coaching',
	'defaultAction' => 'index'
);
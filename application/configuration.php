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

$configuration['defaultQuery'] = 'beziehung/1';

$configuration['aliasQueries'] = array(
	'(Coaching/)?(beziehung|abnehmen)(/(1|0))' => 'Coaching/query/$2$3'
);

$configuration['debugMode'] = TRUE;
//$configuration['debugMode'] = FALSE;
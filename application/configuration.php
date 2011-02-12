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

$configuration['defaultQuery'] = 'beziehung';

$configuration['aliasQueries'] = array(
	'(Coaching/)?(beziehung|abnehmen)' => 'Coaching/query/$2',
	'signIn' => 'Authentication/signIn/simon@notmybiz.com/test',
	'signOut' => 'Authentication/signOut',
	'([^/]+)' => '$1/index'
);

$configuration['debugMode'] = TRUE;
//$configuration['debugMode'] = FALSE;
<?php

require_once 'configuration/locals.php';
require_once 'configuration/database.php';
require_once 'configuration/settings.php';
require_once 'Application.php';

function __autoload($className) {
	if (strpos('Finder', $className) !== FALSE && (file_exists($fileName = 'Finders/' . $className . 'Finder.php'))) include_once $fileName;
	else if (strpos('Controller', $className) !== FALSE && (file_exists($fileName = 'Controllers/' . $className . 'Controller.php'))) include_once $fileName;
	else if (file_exists($fileName = 'Models/' . $className . '.php')) include_once $fileName;
	else if (file_exists($fileName = 'Helpers/' . $className . '.php')) include_once $fileName;
}

$Application = new Application($configuration, $_GET['request']);
$Application->run();
<?php

error_reporting(E_ALL);

require_once 'configuration/locals.php';
require_once 'configuration/settings.php';
require_once 'Application.php';

function __autoload($className) {
	if (($namePart = strstr($className, 'Finder', TRUE)) !== FALSE) {
		if (file_exists($fileName = 'Finders/' . $namePart . 'Finder.php')) include_once $fileName;
	} else if (($namePart = strstr($className, 'Controller', TRUE)) !== FALSE) {
		if (file_exists($fileName = 'Controllers/' . $namePart . 'Controller.php')) include_once $fileName;
	} else {
		if (file_exists($fileName = 'Models/' . $className . '.php')) include_once $fileName;
		else if (file_exists($fileName = 'Helpers/' . $className . '.php')) include_once $fileName;
	}
}

$Application = new Application($configuration, isset($_GET['request']) ? $_GET['request'] : NULL);
$Application->run();
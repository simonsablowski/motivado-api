<?php

error_reporting(E_ALL);

require_once dirname(__FILE__) . '/../configuration.php';
foreach (array('Application', 'Api') as $fileName) {
	foreach ($configuration['includeDirectories'] as $includeDirectory) {
		if (file_exists($filePath = $includeDirectory . $fileName . '.php')) break include $filePath;
	}
}

$Api = new \Motivado\Api\Api($configuration, isset($_GET['localization']) ? $_GET['localization'] : NULL);
$Api->query(isset($_GET['CoachingKey']) ? $_GET['CoachingKey'] : NULL);
<? echo Json::encode(array(
	'error' => array(
		'type' => $this->localize($Error->getType()),
		'message' => $this->localize($Error->getMessage())
	)
));
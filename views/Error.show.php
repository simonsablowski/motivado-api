<? echo Json::encode(array(
	'response' => array(
		'status' => $this->localize('Failure'),
		'error' => array(
			'type' => $Error->getType(),
			'message' => $Error->getMessage()
		)
	)
)); ?>
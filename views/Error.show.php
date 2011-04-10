<? echo Json::encode(array(
	'response' => array(
		'status' => $this->localize('Failure'),
		'error' => array(
			'type' => $this->localize($Error->getType()),
			'message' => $this->localize($Error->getMessage())
		)
	)
)); ?>
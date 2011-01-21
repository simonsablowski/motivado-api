<?php

class Video extends Model {
	protected $fields = array(
		'title',
		'url',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'title',
		'url'
	);
}
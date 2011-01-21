<?php

class ObjectsVideo extends Model {
	protected $primaryKey = array(
		'ObjectId',
		'VideoId'
	);
	protected $fields = array(
		'ObjectId',
		'VideoId',
		'status',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'ObjectId',
		'VideoId'
	);
	
	protected $Object = NULL;
	protected $Video = NULL;
}
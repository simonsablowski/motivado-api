<?php

class ObjectsVideoFinder extends Finder {
	protected static $tableName = 'objectsvideo';
	protected static $primaryKey = array(
		'ObjectId',
		'VideoId'
	);
}
<?php

class User extends Model {
	protected $fields = array(
		'id',
		'eMailAddress',
		'password',
		'firstName',
		'lastName',
		'temporary',
		'status',
		'created',
		'modified'
	);
	protected $hiddenFields = array(
		'id',
		'password',
		'status',
		'created',
		'modified'
	);
	
	protected $Coachings = NULL;
	protected $Interactions = NULL;
	
	public function __construct() {
		parent::__construct(func_num_args() == 1 && is_array($argument = func_get_arg(0)) ? $argument : func_get_args());
		
		$this->setTemporary('no');
	}
	
	public function loadCoachings() {
		$this->Coachings = array();
		$UsersCoachings = UsersCoaching::findAll(array(
			'UserId' => $this->getId()
		));
		foreach ($UsersCoachings as $UsersCoaching) {
			$this->Coachings[] = $UsersCoaching->getCoaching();
		}
	}
	
	public function loadInteractions() {
		$this->Interactions = UsersInteraction::findAll(array(
			'UserId' => $this->getId()
		));
	}
}
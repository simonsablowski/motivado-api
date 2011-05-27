<?php

namespace Motivado\Api;

class Api extends \Application {
	public function query($CoachingKey, $initial = TRUE) {
		return $this->run(sprintf('\Motivado\Api\Coaching/query/%s/%d', $CoachingKey, (int)$initial));
	}
	
	public function extendCoachingHistory($CoachingKey, $ObjectId) {
		return $this->run(sprintf('\Motivado\Api\Coaching/extendCoachingHistory/%s/%d', $CoachingKey, $ObjectId));
	}
}
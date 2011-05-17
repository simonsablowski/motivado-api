<?php

class Api extends Application {
	public function query($CoachingKey) {
		return $this->run(sprintf('Coaching/query/%s', $CoachingKey));
	}
	
	public function extendCoachingHistory($CoachingKey, $ObjectId) {
		return $this->run(sprintf('Coaching/extendCoachingHistory/%s/%d', $CoachingKey, $ObjectId));
	}
}
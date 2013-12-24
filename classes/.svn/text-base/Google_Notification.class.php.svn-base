<?php
// classes/Google_Notification.class.php
require_once('HttpPost.class.php');

/**
 * The Google Timeline Notification Level
 * 
 */
class Google_Notification {
	
	
	const LEVEL_DEFAULT = "DEFAULT";
	
	// these are the variables that will come back from the server
	public $level = LEVEL_DEFAULT;

	
	public function fromJSONObject($jsonObject) {
		$this->level = $jsonObject->level;
	}
	
	
	public function fromJSON($json) {
		$this->fromJSONObject(json_decode($json));
	}
	
	
	public function toJSONObject() {
		if ($this->level) $postData['level'] = $this->level;
		
		return $postData;
	}
	
	
	
}

?>
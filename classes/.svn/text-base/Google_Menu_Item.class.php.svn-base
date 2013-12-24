<?php
// classes/Google_Menu_Item.class.php
require_once('Google_Menu_Item_Value.class.php');

/**
 * Google Timeline Card Menu Item
 * 
 */
class Google_Menu_Item {
	
	
	const ACTION_CUSTOM = "CUSTOM";
	const ACTION_REPLY = "REPLY";
	const ACTION_REPLY_ALL = "REPLY_ALL";
	const ACTION_DELETE = "DELETE";
	const ACTION_SHARE = "SHARE";
	const ACTION_READ_ALOUD = "READ_ALOUD";
	const ACTION_VOICE_CALL = "VOICE_CALL";
	const ACTION_NAVIGATE = "NAVIGATE";
	const ACTION_TOGGLE_PINNED = "TOGGLE_PINNED";
	const ACTION_OPEN_URI = "OPEN_URI";
	const ACTION_PLAY_VIDEO = "PLAY_VIDEO";
	
	
	// these are the variables that will come back from the server
	public $id,
		$action,
		$payload,
		$removeWhenSelected,
		$values = array();
		


	public function fromJSONObject($jsonObject) {
		$this->id = $jsonObject->id;
		$this->action = $jsonObject->action;
		$this->payload = $jsonObject->payload;
		$this->removeWhenSelected = $jsonObject->removeWhenSelected;
		
		$this->values = array();
		if ($jsonObject->values) foreach ($jsonObject->values as $value) {
			$Value = new Google_Menu_Item_Value();
			$Value->fromJSONObject($value);		
			$this->values[] = $Value;	
		}
	}
	
	
	public function fromJSON($json) {
		$this->fromJSONObject(json_decode($json));
	}
	
	
	public function toJSONObject() {
		if ($this->id) $postData['id'] = $this->id;
		if ($this->action) $postData['action'] = $this->action;
		if ($this->payload) $postData['payload'] = $this->payload;
		if ($this->removeWhenSelected) $postData['removeWhenSelected'] = $this->removeWhenSelected;
		
		if ($this->values) foreach ($this->values as $value) {
			$postData['values'][] = $value->toJSONObject();			
		}
		
		return $postData;
		
	}
	
	
}

?>
<?php
// classes/Google_Menu_Value.class.php

/**
 * Google Timeline Menu Item Value
 * 
 */
class Google_Menu_Item_Value {
	
	
	const STATE_DEFAULT = "DEFAULT";
	const STATE_PENDINGT = "PENDING";
	const STATE_CONFIRMED = "CONFIRMED";
	
	
	// these are the variables that will come back from the server
	public $displayName,
		$iconUrl,
		$state = STATE_DEFAULT;


	public function fromJSONObject($jsonObject) {
		$this->displayName = $jsonObject->displayName;
		$this->iconUrl = $jsonObject->iconUrl;
		$this->state = $jsonObject->state;
	}
	
	
	public function fromJSON($json) {
		$this->fromJSONObject(json_decode($json));
	}
	
	public function toJSONObject() {
		if ($this->displayName) $postData['displayName'] = $this->displayName;
		if ($this->iconUrl) $postData['iconUrl'] = $this->iconUrl;
		if ($this->state) $postData['state'] = $this->state;
		
		return $postData;
	}
	
	
}

?>
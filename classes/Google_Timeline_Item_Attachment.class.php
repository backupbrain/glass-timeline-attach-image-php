<?php
// classes/Google_Timeline_Item_Attachment.class.php

/**
 * Timeline Card attachment
 * 
 */
class Google_Timeline_Item_Attachment {

	// we can only grab userinfo from an authenticated user
	public $Google_OAuth2_Token;

	// these are the variables that will come back from the server
	public $id,
		$contentType,
		$contentUrl,
		$content;

		
	/**
	 * Use the authenticated Google_OAuth2_Token
	 */
	public function __construct($Google_OAuth2_Token) {
		$this->Google_OAuth2_Token = $Google_OAuth2_Token;
	}

	public function fromJSONObject($jsonObject) {
		$this->id = $jsonObject->id;
		$this->contentType = $jsonObject->contentType;
		$this->contentUrl = $jsonObject->contentUrl;
	}
	
	
	public function fromJSON($json) {
		$this->fromJSONObject(json_decode($json));
	}
	
	public function toJSONObject() {
		if ($this->id) $postData['id'] = $this->id;
		if ($this->contentType) $postData['contentType'] = $this->contentType;
		if ($this->contentUrl) $postData['contentUrl'] = $this->contentUrl;
		return $postData;
	}
	
	public function fetchContent() {
		if (!$this->id) {
			throw new Exception("No attachment has been loaded.  You must first grab a Google_Timeline_Item");
		}
		
		// we will be stending the OAuth2 access_token through the HTTP headers
		$headers = array(
			'Authorization: '.$this->Google_OAuth2_Token->token_type.' '.$this->Google_OAuth2_Token->access_token
		);

		$HttpPost = new HttpPost($this->contentUrl);
		$HttpPost->setHeaders( $headers );

		if ($this->Google_OAuth2_Token->authenticated) {
			$HttpPost->send();
		    $this->content = $HttpPost->httpResponse;
		

		} else {
			throw new Exception ("Google_OAuth2_Token needs to be authenticated before you can fetch images.");
		}
	}
	
	public function parseFile($filename) {
		
		$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
		$this->contentType = finfo_file($finfo, $filename);
		finfo_close($finfo);
		
		if (!$this->content = file_get_contents($filename)) {
			throw new Error("Could not open file: '".$filename."'");
		}
	}
	
}

?>
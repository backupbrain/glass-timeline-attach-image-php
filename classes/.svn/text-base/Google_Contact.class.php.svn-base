<?php
// classes/Google_Contact.class.php
require_once('HttpPost.class.php');

/**
 * Perform actions on the user's contact list via
 * https://www.googleapis.com/mirror/v1/contacts
 * 
 * Requires that user has been OAuth2 authenticated
 */
class Google_Contact {
	
	const URL = 'https://www.googleapis.com/mirror/v1/contacts';
	public $fetched = false;
	
	// this is the scope required to access the userinfo
	public static $scopes = array(
		'https://www.googleapis.com/auth/glass.timeline'
	); 
	
	// we can only grab userinfo from an authenticated user
	public $Google_OAuth2_Token;
	
	// these are the variables we are sending
	public $id,
		$kind = 'mirror#contact',
		$displayName,
		$imageUrls = array(),
		$priority;
	
	
	
		
	/**
	 * Use the authenticated Google_OAuth2_Token
	 */
	public function __construct($Google_OAuth2_Token=null) {
		if ($Google_OAuth2_Token) {
			$this->Google_OAuth2_Token = $Google_OAuth2_Token;
		}
	}
	
	public function addImageUrl($url) {
		// replace a url if it already exists
		if ($index = array_search($url, $this->imageUrls) !== false) {
			$this->imageUrls[$index] = $url;
		} else {
			$this->imageUrls[] = $url;
		}
		
	}
	public function removeImageUrl($url) {
		if ($index = array_search($url, $this->imageUrls) !== false) {
			unset($this->imageUrls[$index]);
		}
	}
	
	/**
	 * fetch a contact by id
	 */
	public function get() {
		
		// we will be stending the OAuth2 access_token through the HTTP headers
		$headers = array(
			'Authorization: '.$this->Google_OAuth2_Token->token_type.' '.$this->Google_OAuth2_Token->access_token
		);
		
		$this->HttpPost = new HttpPost(self::URL.'/'.$this->id);

		$this->HttpPost->setHeaders( $headers );
		
		
		if ($this->Google_OAuth2_Token->authenticated) {
			$this->HttpPost->send();
		    $response = json_decode($this->HttpPost->httpResponse);
		
		} else {
			throw new Exception ("Google_OAuth2_Token needs to be authenticated before you can fetch locations.");
		}

		
		// is there an error here?
		if ($response->error) {
			print_r($response->error);
			throw new Exception("The server reported an error: '".$response->error->errors[0]->message."'");
		} else {
			// we can validate the contact by checking that the 
			// response properties matched the properties from this class
			$this->fromJSONObject($response);
		}
		
	}
	
	/**
	 * Fetch the Google+ profile information
	 */
	public function insert() {	
		$postData = array(
			'id' => $this->id,
			'kind' => $this->kind,
			'displayName' => $this->displayName,
			'imageUrls' => $this->imageUrls,
			'priority' => $this->priority
		);
		$json = json_encode($postData);
		
		// we will be stending the OAuth2 access_token through the HTTP headers
		$headers = array(
			'Authorization: '.$this->Google_OAuth2_Token->token_type.' '.$this->Google_OAuth2_Token->access_token,
			'Content-Type: application/json',
			'Content-length: '. strlen($json)
		);
		
		$this->HttpPost = new HttpPost(self::URL);
		$this->HttpPost->setHeaders( $headers );
		$this->HttpPost->setRawPostData( $json );
		
		if ($this->Google_OAuth2_Token->authenticated) {
			$this->HttpPost->send();
		    $response = json_decode($this->HttpPost->httpResponse);
		
		} else {
			throw new Exception ("Google_OAuth2_Token needs to be authenticated before you can fetch locations.");
		}

		
		
		// is there an error here?
		if ($response->error) {
			print_r($response->error);
			throw new Exception("The server reported an error: '".$response->error->errors[0]->message."'");
		} else {
			// we can validate the contact by checking that the 
			// response properties matched the properties from this class
			if (($response->id != $this->id) or
				($response->displayName != $this->displayName)) {
				throw new Exception("Retrieved contact does not match what was inserted");
			} else {
				$this->source = $response->source;
			}
		}
	}
	
	
	
	public function fromJSONObject($jsonObject) {
		$this->kind = $jsonObject->kind;
		$this->source = $jsonObject->source;
		$this->id = $jsonObject->id;
		$this->displayName = $jsonObject->displayName;
		$this->priority = $jsonObject->priority;
		$this->imageUrls = $jsonObject->imageUrls;
	}
	
	
	public function fromJSON($json) {
		$this->fromJSONObject(json_decode($json));
	}
	
	
	public function toJSONObject() {
		if ($this->id) $postData['id'] = $this->id;
		if ($this->kind) $postData['kind'] = $this->kind;
		if ($this->displayName) $postData['displayName'] = $this->displayName;
		if ($this->priority) $postData['priority'] = $this->priority;
		
		if ($this->imageUrls) foreach ($this->imageUrls as $imageUrl) {
			$postData['imageUrls'][] = $imageUrl->toJSONObject();			
		}
		
		return $postData;
		
	}
	
	
}

?>
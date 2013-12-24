<?php
// classes/Google_OAuth2_Token.class.php
require_once('HttpPost.class.php');


/**
 * Authenticate with the Google servers using OAuth2
 * https://accounts.google.com/o/oauth2/token
 * 
 */
class Google_OAuth2_Token {
	
	const URL = 'https://accounts.google.com/o/oauth2/token';
	public $authenticated = false;

	
	// variables that we are sending to the server
	public $code,
		$client_id,
		$client_secret,
		$redirect_uri,
		$grant_type = 'authorization_code';
		
	// variables that will come from the server
	public $access_token,
		$token_type,
		$expires_in,
		$id_token;
	

	/**
	 * Make a HTTP request to the auth url and store the results
	 */
	public function authenticate() {
		$this->HttpPost = new HttpPost(self::URL);
		
		$this->HttpPost->addPostParameter('code', $this->code);
		$this->HttpPost->addPostParameter('client_id', $this->client_id);
		$this->HttpPost->addPostParameter('client_secret', $this->client_secret);
		$this->HttpPost->addPostParameter('redirect_uri', $this->redirect_uri);
		$this->HttpPost->addPostParameter('grant_type', $this->grant_type);
		$this->HttpPost->send();
		
		
	    $response = json_decode($this->HttpPost->httpResponse);
		
		// is there an error here?
		if ($response->error) {
			throw new Exception("The server reported an error: '".$response->error."'");
		} else {
			$this->access_token = $response->access_token;
			$this->token_type = $response->token_type;
			$this->expires_in = $response->expires_in;
			$this->id_token = $response->id_token;
			
			$this->authenticated = true;
		}
	}



}


?>
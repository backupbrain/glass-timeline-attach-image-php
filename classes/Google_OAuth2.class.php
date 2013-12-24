<?php
// classes/Google_OAuth2.class.php
require_once('HttpPost.class.php');



/**
 * Authenticate with the Google servers using OAuth2
 * https://accounts.google.com/o/oauth2/auth
 * 
 */
class Google_OAuth2 {
	
	const URL = 'https://accounts.google.com/o/oauth2/auth';
	public $authenticated = false;

	
	// these variables will be encoded into a GET query
	public $response_type = "code",
		$access_type = "offline",
		$client_id,
		$redirect_uri;
	public $scopes = array();
		
	/**
	 * add a URL OAuth2 scope to the scope list.
	 * @param $scope the URL string for an OAuth2 scope
	 */
	public function addScope($scope) {
		// replace a scope if it already exists
		if ($index = array_search($scope, $this->scopes) !== false) {
			$this->scopes[$index] = $scope;
		} else {
			$this->scopes[] = $scope;
		}
	}
	/**
	 * add multiple URL OAuth2 scopes to the scope list
	 * 
	 * @param $scopes the array of URL strings for OAuth2 scopes
	 */
	public function addScopes($scopes) {
		foreach ($scopes as $scope) {
			$this->addScope($scope);
		}
	}
	
	/**
	 * make the OAuth2 login request.  
	 * This is done by forwarding the user
	 * to a login screen
	 */
	public function authenticate() {
		$scope = implode(" ", $this->scopes);
		
		$query_params = array(
			'response_type' => $this->response_type,
			'access_type' => 'offline',
			'client_id' => $this->client_id,
			'redirect_uri' => $this->redirect_uri,
			'scope' => $scope
		);
		
		
		$forward_url = self::URL . '?' . http_build_query($query_params);
		
		header('Location: ' . $forward_url);
	}


}


?>
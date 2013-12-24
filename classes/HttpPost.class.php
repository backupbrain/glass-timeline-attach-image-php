<?php
// HttpPost.class.php

/**
 * This class sends and receives HTTP data to a web server
 * It uses cURL to transmit HTTP headers and POST to the server
 * then retrieve the result from the server
 */
class HttpPost {
	public $url;
	public $headers;
	public $postString;
	public $postParameters;
	public $getString;
	public $getParameters;
	public $files;
	public $filesString;
	public $httpResponse;

	public $ch;

	/**
	 * Use cURL to send data to to a web server
	 */
	public function __construct($url) {
	         $this->url = $url;
	         $this->ch = curl_init( $this->url );
	         curl_setopt( $this->ch, CURLOPT_FOLLOWLOCATION, false );
	         curl_setopt( $this->ch, CURLOPT_HEADER, false );
	         curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER, true );
	}


	/**
	 * close the curl connection
	 */
	public function __destruct() {
		curl_close($this->ch);
	}    
	
	/**
	 * Set the HTTP headers
	 * 
	 * @param $headers an array of headers
	 */
	public function setHeaders( $headers ) {
		$this->headers = $headers;
		curl_setopt( $this->ch, CURLOPT_HTTPHEADER, $headers );
	}
	
	
	/**
	 * Add the get parameters
	 */
	public function addGetParameter($key, $value) {
		$this->getParameters[$key] = $value;
		$this->setGetData($this->getParameters);
	}
	
	
	/**
	 * Set the GET query string
	 *
	 * @param $getString an url encoded string
	 */
	public function setRawGetData( $getString ) {
		$this->getString = $getString;
		curl_setopt( $this->ch, CURLOPT_URL, $this->url . '?' . $this->getString );
	}
	
	/**
	 *  Set the GET data for the request
	 * 
	 * @param $params an associative array of POST parameters
	 */
	public function setGetData( $params ) {
		$this->getString = http_build_query($params);
		// http_build_query encodes URLs, which breaks POST data
		$this->setRawGetData( http_build_query( $params ) );
	}
	
	
	/**
	 * Add the post parameters
	 */
	public function addPostParameter($key, $value) {
		$this->postParameters[$key] = $value;
		$this->setPostData($this->postParameters);
	}
	
	
	/**
	 * Set the POST field
	 *
	 * @param $postString an url encoded string
	 */
	public function setRawPostData( $postString ) {
		$this->postString = $postString;
		curl_setopt( $this->ch, CURLOPT_POST, true );
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS, $this->postString );
	}
	
	/**
	 *  Set the POST data for the request
	 * 
	 * @param $params an associative array of POST parameters
	 */
	public function setPostData( $params ) {
		$this->postString = http_build_query($params);
		// http_build_query encodes URLs, which breaks POST data
		$this->setRawPostData( urldecode(http_build_query( $params )) );
	}

	
	/**
	 * Add the FILES parameters
	 */
	public function addFileParameter($key, $value) {
		$this->files[$key] = $value;
		$this->setFilesData($this->postParameters);
	}
	
	
	/**
	 * Set the FILES field
	 *
	 * @param $filesString an url encoded string
	 */
	public function setRawFilesData( $filesString ) {
		$this->filesString = $filesString;
	}
	
	/**
	 *  Set the files data for the request
	 * 
	 * @param $params an associative array of FILES parameters
	 */
	public function setFilesData( $params ) {
		$this->filesString = json_encode($params);
		$this->setRawFilesData($this->filesString );
	}

	

	/**
	 * send the HTTP request to the server
	 */
	public function send() {
		$this->httpResponse = curl_exec( $this->ch );
	}

}

?>


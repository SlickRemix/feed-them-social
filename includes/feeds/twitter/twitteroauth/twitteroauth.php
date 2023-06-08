<?php
namespace feedthemsocial;
/*
 * Abraham Williams (abraham@abrah.am) http://abrah.am
 *
 * The first PHP Library to support OAuth for Twitter's REST API.
 */

/* Load OAuth lib. You can find it at http://oauth.net */
require_once( 'OAuth.php' );

/**
 * Twitter OAuth class
 */

	class TwitterOAuthFTS {
	  /* Contains the last HTTP status code returned. */
	  public $http_code;
	  /* Contains the last API call. */
	  public $url;
	  /* Set up the API root URL. */
	  public $host = TWITTER_V2 ? "https://api.twitter.com/2/" : "https://api.twitter.com/1.1/";
	  /* Set timeout default. */
	  public $timeout = 30;
	  /* Set connect timeout. */
	  public $connecttimeout = 30; 
	  /* Verify SSL Cert. */
	  public $ssl_verifypeer = FALSE;
	  /* Respons format. */
	  public $format = 'json';
	  /* Decode returned json data. */
	  public $decode_json = TRUE;
	  /* Contains the last HTTP headers returned. */
	  public $http_info;
	  /* Set the useragnet. */
	  public $useragent = 'TwitterOAuthFTS v0.2.0-beta2';
	  /* Immediately retry the API call if the response was not successful. */
	  //public $retry = TRUE;
	
	
	
	
	  /**
	   * Set API URLS
	   */
	  function accessTokenURL()  { return 'https://api.twitter.com/oauth/access_token'; }
	  function authenticateURL() { return 'https://api.twitter.com/oauth/authenticate'; }
	  function authorizeURL()    { return 'https://api.twitter.com/oauth/authorize'; }
	  function requestTokenURL() { return 'https://api.twitter.com/oauth/request_token'; }
	
	  /**
	   * Debug helpers
	   */
	  function lastStatusCode() { return $this->http_status; }
	  function lastAPICall() { return $this->last_api_call; }
	
	  /**
	   * construct TwitterOAuthFTS object
	   */
	  function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
		$this->sha1_method = new OAuthSignatureMethodFTS_HMAC_SHA1();
		$this->consumer = new OAuthConsumerFTS($consumer_key, $consumer_secret);
		if (!empty($oauth_token) && !empty($oauth_token_secret)) {
		  $this->token = new OAuthConsumerFTS($oauth_token, $oauth_token_secret);
		} else {
		  $this->token = NULL;
		}
	  }
	
	
	  /**
	   * Get a request_token from Twitter
	   *2
	   * @returns a key/value array containing oauth_token and oauth_token_secret
	   */
	  function getRequestToken($oauth_callback = NULL) {
		$parameters = array();
		if (!empty($oauth_callback)) {
		  $parameters['oauth_callback'] = $oauth_callback;
		} 
		$request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
		$token = OAuthUtilFTS::parse_parameters($request);
		$this->token = new OAuthConsumerFTS($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	  }
	
	  /**
	   * Get the authorize URL
	   *
	   * @returns a string
	   */
	  function getAuthorizeURL($token, $sign_in_with_twitter = TRUE) {
		if (is_array($token)) {
		  $token = $token['oauth_token'];
		}
		if (empty($sign_in_with_twitter)) {
		  return $this->authorizeURL() . "?oauth_token={$token}";
		} else {
		   return $this->authenticateURL() . "?oauth_token={$token}";
		}
	  }
	
	  /**
	   * Exchange request token and secret for an access token and
	   * secret, to sign API calls.
	   *
	   * @returns array("oauth_token" => "the-access-token",
	   *                "oauth_token_secret" => "the-access-secret",
	   *                "user_id" => "9436992",
	   *                "screen_name" => "abraham")
	   */
	  function getAccessToken($oauth_verifier = FALSE) {
		$parameters = array();
		if (!empty($oauth_verifier)) {
		  $parameters['oauth_verifier'] = $oauth_verifier;
		}
		$request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
		$token = OAuthUtilFTS::parse_parameters($request);
		$this->token = new OAuthConsumerFTS($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	  }
	
	  /**
	   * One time exchange of username and password for access token and secret.
	   *
	   * @returns array("oauth_token" => "the-access-token",
	   *                "oauth_token_secret" => "the-access-secret",
	   *                "user_id" => "9436992",
	   *                "screen_name" => "abraham",
	   *                "x_auth_expires" => "0")
	   */  
	  function getXAuthToken($username, $password) {
		$parameters = array();
		$parameters['x_auth_username'] = $username;
		$parameters['x_auth_password'] = $password;
		$parameters['x_auth_mode'] = 'client_auth';
		$request = $this->oAuthRequest($this->accessTokenURL(), 'POST', $parameters);
		$token = OAuthUtilFTS::parse_parameters($request);
		$this->token = new OAuthConsumerFTS($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	  }


	  /**
	   * Wrapper for fetching data from Twitter API v 2.0
	   *
	   * @param [type] $url
	   * @param array $parameters
	   * @return void
	   */
	  function getv2($url, $parameters = array()) {

	  }

	
	  /**
	   * GET wrapper for oAuthRequest.
	   */
	  function get($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
		  return json_decode($response);
		}
		return $response;
	  }
	  
	  /**
	   * POST wrapper for oAuthRequest.
	   */
	  function post($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'POST', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
		  return json_decode($response);
		}
		return $response;
	  }
	
	  /**
	   * DELETE wrapper for oAuthReqeust.
	   */
	  function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
		  return json_decode($response);
		}
		return $response;
	  }
	
	  /**
	   * Format and sign an OAuth / API request
	   */
	  function oAuthRequest($url, $method, $parameters) {
		if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
			if(TWITTER_V2) {
				$url = "{$this->host}{$url}";
			} else {
				$url = "{$this->host}{$url}.{$this->format}";
			}			
		}
		
		
		switch ($method) {
		case 'GET':
			if(TWITTER_V2) {
				// todo - send this data along in the headers
				
				$request = OAuthRequestFTS::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
				$request->sign_request($this->sha1_method, $this->consumer, $this->token);
				$params = $request->get_parameters();

				// $request->to_url() - matches the params
				// apply urlencode to all of $param

				$params['oauth_signature'] = urlencode($params['oauth_signature']);

				//$header = ["Authorization: OAuth oauth_consumer_key='{$params['oauth_consumer_key']}', oauth_nonce='{$params['oauth_nonce']}', oauth_signature='{$params['oauth_signature']}', oauth_signature_method='HMAC-SHA1', oauth_timestamp='{$params['oauth_timestamp']}', oauth_token='{$params['oauth_token']}', oauth_version='1.0'"];

				//$header = ["Authorization: Bearer ".TWITTER_BEARER_TOKEN];
				$header = ["Authorization: Bearer ".get_option('user_bearer_token')];
				
				
				$fullUrl = $url."?".http_build_query($parameters);
				
				$response = $this->http_v2($fullUrl, $header);
				
				//var_dump($header);
				//var_dump($response);
				//exit;

				return $response;
			} else {
				$request = OAuthRequestFTS::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
				$request->sign_request($this->sha1_method, $this->consumer, $this->token);
				return $this->http($request->to_url(), 'GET');
			}
		  
		default:
		  return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
		}
	  }


	/**
	 * HTTP Get function for Api V2
	 * 
	 * Since none of the funcitonality uses POST, we can simplify this down to only the necessary
	 * curl options
	 *
	 * @return void
	 */
	function http_v2($url, $header = []) {
		
		$ci = curl_init();
		$this->http_info = array();
		
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);
		curl_setopt($ci, CURLOPT_HTTPHEADER, $header);	
		curl_setopt($ci, CURLOPT_URL, $url);
		$response = curl_exec($ci);
		
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		curl_close ($ci);
		return $response;	

	}



	
	  /**
	   * Make an HTTP request
	   *
	   * @return API results
	   */
	  function http($url, $method, $postfields = NULL, $headers = []) {
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		$header = array('Expect:');
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		// append $headers to $header array
		if (!empty($headers)) {
			$header = array_merge($header, $headers);
		}
	
		switch ($method) {
		  case 'POST':
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if (!empty($postfields)) {
			  curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
			}
			break;
		  case 'DELETE':
			curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
			if (!empty($postfields)) {
			  $url = "{$url}?{$postfields}";
			}
		}
	

		//var_Dump($header);
		//var_dump($url);
		curl_setopt($ci, CURLOPT_HTTPHEADER, $header);
	
		curl_setopt($ci, CURLOPT_URL, $url);
		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		curl_close ($ci);
		return $response;
	  }
	
	  /**
	   * Get the header info to store.
	   */
	  function getHeader($ch, $header) {
		$i = strpos($header, ':');
		if (!empty($i)) {
		  $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
		  $value = trim(substr($header, $i + 2));
		  $this->http_header[$key] = $value;
		}
		return strlen($header);
	  }
}
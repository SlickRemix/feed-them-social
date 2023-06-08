<?php
/**
 * Template name: Twitter OAuth2
 *
 * Install this template within the main site which will be handling the authentication
 * requests from the plugin installations.
 * 
 * Ensure that you have configured TWITTER_CLIENT_ID and TWITTER_CLIENT_SECRET in your wp-config.php
 * 
 */

session_start();

require_once 'vendor/autoload.php';

// Example URL - 
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url = strtok($url, '?');
$url = rtrim($url, "/");

$provider = new Smolblog\OAuth2\Client\Provider\Twitter([
	'clientId'          => TWITTER_CLIENT_ID,
	'clientSecret'      => TWITTER_CLIENT_SECRET,
	'redirectUri'       => $url,
]);


if (!isset($_GET['code'])) {
	
	unset($_SESSION['oauth2state']);
	unset($_SESSION['oauth2verifier']);

	if(!isset($_GET['redirect_url'])) {
		die('No redirect URL set');
	}
	
	$state = strpos( $_GET['redirect_url'], 'post' ) ? $_GET['redirect_url'] . '&action=edit&feed_type=twitter' : $_GET['redirect_url'];
	$_SESSION['redirect'] = $state;
	
	$options = [
		'scope' => [
			'tweet.read', // requires basic $100/mo tier
			'users.read', // can be accessed through free tier (good for testing the API works)
			'offline.access', // required to allow for refresh_token to persist past two hours
		],
	];

	// If we don't have an authorization code then get one
	$authUrl = $provider->getAuthorizationUrl($options);
	$_SESSION['oauth2state'] = $provider->getState();

	// We also need to store the PKCE Verification code so we can send it with
	// the authorization code request.
	$_SESSION['oauth2verifier'] = $provider->getPkceVerifier();

	header('Location: '.$authUrl);
	exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

	unset($_SESSION['oauth2state']);
	exit('Invalid state');

} else {

	try {
        
		// Try to get an access token (using the authorization code grant)
		// This will be the bearer token which the plugin can use on behalf of the
		// user to look up tweets
		$token = $provider->getAccessToken('authorization_code', [
			'code' => $_GET['code'],
			'code_verifier' => $_SESSION['oauth2verifier'],
		]);

		$redirect = $_SESSION['redirect'] . '&user_refresh_token='. $token->getRefreshToken() .'&user_bearer_token=' . $token->getToken();
		header('Location: '.$redirect);
		exit;

	} catch (Exception $e) {
		echo $e->getMessage();
		exit;
	}

	// Use this to interact with an API on the users behalf
	//echo $token->getToken();
    
}
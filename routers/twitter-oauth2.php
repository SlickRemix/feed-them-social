<?php

/**
 * Template name: Twitter OAuth2
 *
 * Install this template within the main site which will be handling the authentication
 * requests from the plugin installations.
 * 
 * Make sure that the path it is available under matches the path defined within the main
 * plugin codebase.
 * 
 * Ensure that you have configured TWITTER_CLIENT_ID and TWITTER_CLIENT_SECRET in your wp-config.php
 * 
 * This file also requires smolblog/oauth2-twitter^1.1 to be installed via composer.
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


if (isset($_GET['refresh'])) {


	$appAuth = base64_encode(TWITTER_CLIENT_ID . ":" . TWITTER_CLIENT_SECRET);
	
	$curl = curl_init("https://api.twitter.com/2/oauth2/token");
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/x-www-form-urlencoded',
		"Authorization: Basic $appAuth"
	));
	
	$postFields = http_build_query(array(
		"refresh_token" => $_GET['refresh'],
		"grant_type" => "refresh_token",
		"client_id" => TWITTER_CLIENT_ID
	));
	
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);

	$content = curl_exec($curl);
	$accessTokenObject = json_decode($content);

	// json header
	header('Content-Type: application/json');

	echo json_encode($accessTokenObject);

	exit;
}


if (!isset($_GET['code'])) {

	unset($_SESSION['oauth2state']);
	unset($_SESSION['oauth2verifier']);

	if (!isset($_GET['redirect_url'])) {
		die('No redirect URL set');
	}

	$state = strpos($_GET['redirect_url'], 'post') ? $_GET['redirect_url'] . '&action=edit&feed_type=twitter' : $_GET['redirect_url'];
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

	header('Location: ' . $authUrl);
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

		$redirect = $_SESSION['redirect'] . '&user_refresh_token=' . $token->getRefreshToken() . '&user_bearer_token=' . $token->getToken();
		header('Location: ' . $redirect);
		exit;
	} catch (Exception $e) {
		echo $e->getMessage();
		exit;
	}

	// Use this to interact with an API on the users behalf
	//echo $token->getToken();

}
<?php
namespace Rocks\OAuth\Google;

use Scotch\System as System;
use Rocks\Account\AuthProviders as AuthProviders;
use Rocks\OAuth\OAuthClient as OAuthClient;
use Rocks\Models\SessionUser as SessionUser;
use Exception as Exception;
 
class GoogleClient extends OAuthClient
{
	const NONCE_STATE = "state";
	const AUTHORIZATION_ENDPOINT = "https://accounts.google.com/o/oauth2/auth";
	const TOKEN_ENDPOINT         = "https://accounts.google.com/o/oauth2/token"; 
	const USER_DETAILS_ENDPOINT  = "https://www.googleapis.com/plus/v1/people/me";
	
	function __construct()
	{
		$settings = System::$application->configuration->settings["OAuthSettings"]["GoogleOAuthSettings"];
		
		$clientID = $settings["clientID"];
		$clientSecret = $settings["clientSecret"];
		
		parent::__construct($clientID,$clientSecret);
	}
	
	function getAuthorizationEndpoint()
	{
		return self::AUTHORIZATION_ENDPOINT;
	} 
	
	function getAccessTokenEndpoint()
	{
		return self::TOKEN_ENDPOINT;
	}
	
	function getUserDetailsEndpoint()
	{
		return self::USER_DETAILS_ENDPOINT;
	}
	
	function authenticate($redirectUrl,$parameters)
	{	
		$user = null;
		
		if(!isset($_GET["code"]))
		{
			$state = md5(rand()); 
			$_SESSION[self::NONCE_STATE] = $state;
			
			header("Location: " . $this->getAuthenticationUrl($redirectUrl,array_merge(array(
				self::NONCE_STATE => $state
			), $parameters)));
			die("Redirect");
		}
		else
		{
			$state = $_GET["state"];
			if($state == $_SESSION[self::NONCE_STATE])
			{
				$params = array("code" => $_GET["code"], "redirect_uri" => $redirectUrl);
				$response = $this->getAccessToken("authorization_code", $params);
				$info = $response["result"];
				$this->client->setAccessToken($info["access_token"]);
				$response = $this->client->fetch($this->getUserDetailsEndpoint());
				
				$info = $response["result"];
				
				$user = new SessionUser();
				
				$user->authProviderID = AuthProviders::Google;
				$user->authID = $info["id"];
				$user->email = $info["emails"][0]["value"];
				$user->firstName = $info["name"]["givenName"];
				$user->lastName = $info["name"]["familyName"];
				$user->imageUrl = $info["image"]["url"];
			}
			else
			{
				
			}
		}
		
		return $user; 
	}
	
}
?>
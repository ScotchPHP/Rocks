<?php
namespace Rocks\OAuth\Facebook;

use Scotch\System as System;
use Rocks\Account\AuthProviders as AuthProviders;
use Rocks\OAuth\OAuthClient as OAuthClient;
use Rocks\Models\SessionUser as SessionUser;
use Exception as Exception;

class FacebookClient extends OAuthClient
{
	const NONCE_STATE = "state";
	const AUTHORIZATION_ENDPOINT = "https://graph.facebook.com/oauth/authorize";
	const TOKEN_ENDPOINT         = "https://graph.facebook.com/oauth/access_token"; 
	const USER_DETAILS_ENDPOINT  = "https://graph.facebook.com/me";
	
	function __construct()
	{
		$settings = System::$application->configuration->settings["OAuthSettings"]["FacebookOAuthSettings"];
		
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
			),$parameters)));
			die("Redirect");
		}
		else
		{
			$state = $_GET[self::NONCE_STATE];
			
			if($state == $_SESSION[self::NONCE_STATE])
			{
				$params = array("code" => $_GET["code"], "redirect_uri" => $redirectUrl);
				$response = $this->getAccessToken("authorization_code", $params);
				parse_str($response["result"], $info);
				$this->client->setAccessToken($info["access_token"]);
				$response = $this->client->fetch($this->getUserDetailsEndpoint(), array("fields" => "id,email,first_name,last_name,picture"));
				
				$info = $response["result"];
				
				$user = new SessionUser();
				
				$user->authProviderID = AuthProviders::Facebook;
				$user->authID = $info["id"];
				$user->email = $info["email"];
				$user->firstName = $info["first_name"];
				$user->lastName = $info["last_name"];
				
				if(isset($info["picture"]["data"]["url"]))
				{
					$user->imageUrl = $info["picture"]["data"]["url"];
				}
			}
			else
			{
				throw new Exception();
			}
		}
		
		return $user;
	}
	
}
?>
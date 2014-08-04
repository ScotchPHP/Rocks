<?php
namespace Rocks\OAuth;

use Scotch\Security\OAuth\Client as Client;

abstract class OAuthClient 
{
	protected $client;
	
	
	function __construct($clientID, $clientSecret)
	{
		$this->client = new Client($clientID, $clientSecret);
	}
	
	abstract function getAuthorizationEndpoint();
	
	abstract function getAccessTokenEndpoint();
	
	abstract function getUserDetailsEndpoint();
	
	function getAuthenticationUrl($redirectUrl, $parameters = array())
	{
		return $this->client->getAuthenticationUrl($this->getAuthorizationEndPoint(),$redirectUrl,$parameters);
	}
	
	function getAccessToken($grantType, $parameters = array())
	{
		return $this->client->getAccessToken($this->getAccessTokenEndPoint(),$grantType, $parameters);
	}
	
	function getUserDetails()
	{
		return $client->fetch($this->getUserDetailsEndPoint());
	}
	
	abstract function authenticate($redirectUrl,$parameters);
}
?>
<?php
namespace Rocks\Controllers;
use Exception as Exception;

use Scotch\System as System;
use	Scotch\Controllers\Controller as Controller;
use Scotch\ServerVariables as ServerVariables;
use Scotch\Protocols as Protocols;

use Rocks\Security\UserManager as UserManager;
use Rocks\Security\AccountManager as AccountManager;
use Rocks\WorkflowHandler as WorkflowHandler;
use Rocks\ErrorMailMessage as ErrorMailMessage;

abstract class RocksController extends Controller
{
/* constancts */
	const SERVER_PROTOCOL = "SERVER_PROTOCOL";
	const SERVER_HTTP_HOST = "HTTP_HOST";
	const SERVER_SCRIPT_NAME = "SCRIPT_NAME";
	const SERVER_QUERY_STRING = "QUERY_STRING";
	const SERVER_REQUEST_URI = "REQUEST_URI";
	
	const HTTP = "http";
	const HTTPS = "https";
	
/* protected properties */
	protected $config;
	
	protected $sqlSession;
	protected $userManager;
	protected $accountManager;
	protected $workflowHandler;
	
	protected $addToPageCount = true;
	protected $httpsOnly = false;
	
	protected $protocol = Protocols::HTTP;
	protected $hostName = "";
	protected $queryString = "";
	protected $requestUri = "";
	protected $isHttp = true;
	protected $ipAddress = "";

/* public properties */ 
	public $user;
	public $account;
	public $accountID;
	public $accountName;

/* constructor */
	function __construct($router = null)
	{
		parent::__construct($router);		
		
		$this->setUserManager();
		$this->setAccountManager();
		
		//$this->workflowHandler = WorkflowHandler::getInstance($this->sqlSession);
		$this->protocol = (isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] != "off") ? Protocols::HTTPS : Protocols::HTTP;
		$this->hostName = $_SERVER[ServerVariables::HTTP_HOST];
		$this->queryString = $_SERVER[ServerVariables::QUERY_STRING];
		
		// Cleanse Query String of mvc components
		$this->queryString = $this->util->removeQueryStringParameter("r",$this->util->removeQueryStringParameter("m",$this->util->removeQueryStringParameter("c", $this->queryString)));
		$this->requestUri = $_SERVER[ServerVariables::REQUEST_URI];
		$this->isHttp = ($this->protocol === Protocols::HTTP);
		$this->ipAddress =$this->util->getValue($_SERVER, ServerVariables::REMOTE_ADDR);
		
		if($this->addToPageCount)
		{
			$this->addToPageCounter();
		}
		
		$this->user = $this->userManager->user;
		$this->account = $this->accountManager->getSessionAccount();
	}
	
	protected function setUserManager()
	{
		if($this->userManager == null)
		{
			$this->userManager = new UserManager($this->sqlSession);
		}
	}
	
	protected function setAccountManager()
	{
		if($this->accountManager == null)
		{
			$this->accountManager = new AccountManager($this->sqlSession);
		}
	}
	
	protected abstract function getLoginUrl();

/* methods */
	protected function ensureHttps()
	{
		if(System::$application->configuration->httpsEnabled)
		{
			if($this->isHttp)
			{
				// Reroute to https
				header("HTTP/1.1 301 Moved Permanently"); 
				header("Location: https://" . $this->hostName . ((isset($this->requestUri)) ? $this->requestUri : "") );
			}
		}
	}
	
	protected function ensureAuthenticatedUser($redirectUrl = null)
	{
		if (!$this->isUserAuthenticated())
		{ 
			$redirectUrl = $this->util->coalesce($redirectUrl,$this->getLoginUrl());
			$this->redirect($redirectUrl);
		}
	}
	
	protected function ensureAuthenticatedAccount($redirectUrl)
	{
		if (!$this->isAccountAuthenticated())
		{
			$this->redirect($redirectUrl);
		}
	}
	
	protected function ensureAuthenticatedAdmin($redirectUrl)
	{
		if (!$this->isAdminAuthenticated())
		{
			$this->redirect($redirectUrl);
		}
	}
	
	protected function isUserAuthenticated()
	{
		return ($this->user->userID <= 0) ? false : true;
	}
	
	protected function isAccountAuthenticated()
	{
		return (!isset($this->account) || !$this->util->hasValue($this->account->accountID)) ? false : true;
	}
	
	protected function isAdminAuthenticated()
	{
		return ($this->user->isAdmin) ? true : false;
	}
	
	protected function getMessage(&$data, $messageMap, $key = "message", $queryStringParam = "message")
	{
		$val = $this->request($queryStringParam, "int", null);
		
		if($this->hasValue($val))
		{
			$stringLookupKey = $this->util->getValue($messageMap, $val);
			$data[$key] = $this->getString($stringLookupKey);
		}
	}
	
	protected function getErrorMessage(&$data, $messageMap, $key = "errorMessage", $queryStringParam = "error" )
	{
		$this->getMessage($data, $messageMap, $key, $queryStringParam);
	}
	
	protected function getWarningMessage(&$data, $messageMap, $key = "warnMessage", $queryStringParam = "warn" )
	{
		$this->getMessage($data, $messageMap, $key, $queryStringParam);
	}
	
	protected function getInfoMessage(&$data, $messageMap, $key = "infoMessage", $queryStringParam = "info" )
	{
		$this->getMessage($data, $messageMap, $key, $queryStringParam);
	}
	
	protected function addToPageCounter()
	{
		//return false;
	}
	
	protected function sendErrorEmail($message)
	{
		$emailMessage = new ErrorMailMessage(array(
			"message" => $message
		));
		
		$emailMessage->send();
	}
}
?>
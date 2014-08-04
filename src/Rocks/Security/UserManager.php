<?php
namespace Rocks\Security;

use Scotch\System as System;
use Scotch\Utilities\WebUtilities as WebUtilities;
use Scotch\Encryption\Pbkdf2 as Pbkdf2;

use Rocks\Models\SessionUser as SessionUser;
use Rocks\Security\AuthResult as AuthResult;

class UserManager
{	
	/*
	* SESSION CONSTANTS
	*/
	const USER_SESSION = "rocks.user";
	
	private $configuration;
	public $hasAuthenticatedUser;			//is user logged in flag
	private $authenticationService;
	
	public $user;
	
	function __construct($session)
	{
		$this->authenticationService = System::$application->getService("IAuthenticationService");
		
		session_start(); // resume or start session
		
		$this->user = WebUtilities::getInstance()->getValue($_SESSION, self::USER_SESSION);
		
		if (isset($this->user))
		{
			// check if a user is logged in
			if($this->user->userID > 0)
			{
				$this->hasAuthenticatedUser = true;
					
				// check if session id should be updated
				$now = time();
				$startTime = $this->user->startTime;
				$interval = ($now - $startTime);
					
				if($interval >= System::$application->configuration->sessions["sessionTimeout"])
				{
					$this->logout();		// end a session that is older than configured expiration
				}
				else
				{
					$lastActivity = $this->user->lastActivity;
					$interval = ($now - $lastActivity);
					
					if($interval >= System::$application->configuration->sessions["activityTimeout"])
					{
						$this->logout();	// end session that has been inactive for longer than configured expiration
					}
					else
					{
						if($this->user->requestCount >= System::$application->configuration->sessions["requestLimit"])
						{
							$this->updateSessionID();
						}
						else
						{
							$lastUpdate = $this->user->lastUpdate;
							$interval = $now - $lastUpdate;

							if($interval >= System::$application->configuration->sessions["updateTimeout"])
							{
								$this->updateSessionID();		// change session ID every X minutes
							}
						}
						$this->user->requestCount = $this->user->requestCount + 1;
						$this->user->lastActivity = time();
						$this->updateUser();
					}
				}
			}
			else
			{
				// handle anonymous user				
			}
		}
		else
		{
			$this->createAnonymousSession();
		}
	}

/* authentication methods */
	function login($email, $password, $ipAddress)
	{
		$result = $this->authenticationService->getUserAuthenticationInfo(array(
			"email" => $email
		));
		
		if ( !$result->hasError )
		{
			$authInfo = $result->model;
			$result = $this->authenticationService->authenticateUser(array(
				"email" => $email,
				"password" => $this->hashPassword($password, $authInfo->salt),
				"ipAddress" => $ipAddress
			));
			
			if ( !$result->hasError )
			{
				$this->initializeSession($result->model);
			}
		}
		return new AuthResult($result);
	}
	
	function loginOAuth($email, $authProviderID, $authID, $ipAddress, $param = array())
	{
		$result = $this->authenticationService->authenticateUser(array(
			"email" => $email,
			"authProviderID" => $authProviderID,
			"authID" => $authID,
			"imageUrl" => WebUtilities::getInstance()->getValue($param,"imageUrl"),
			"ipAddress" => $ipAddress,
		));
		
		if ( !$result->hasError )
		{
			$this->initializeSession($result->model);
		}
		
		return new AuthResult($result);
	}	
	
	function logout($redirectPath = null)
	{	
		if(isset($_SESSION))
		{
			$cookieParams = session_get_cookie_params();
			setcookie(session_name(), session_id(), time()-10, $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], isset($cookieParams['httponly']));
			
			$_SESSION[self::USER_SESSION] = null;
			session_unset();
			session_destroy();
		}
		
		$this->hasAuthenticatedUser = false;
		
		$redirectPath = WebUtilities::getInstance()->coalesce($redirectPath, "/");
		
		WebUtilities::getInstance()->redirect($redirectPath);
	}
	
	function forgot($email)
	{
		return $this->authenticationService->forgotPassword(array(
			"email" => $email,
		));
	}
	
	function reset($userID, $recoveryKey, $email, $password, $passwordConfirm)
	{
		$passwordResult = $this->generatePassword($password, $passwordConfirm);
		
		return $this->authenticationService->reset(array(
			"userID" => $userID,
			"recoveryKey" => $recoveryKey,
			"email" => $email,
			"password" => $passwordResult["password"],
			"passwordConfirm" => $passwordResult["passwordConfirm"],
			"salt" => $passwordResult["salt"]
		));
	}
	
	function register($registration)
	{
		$password = null;
		$passwordConfirm = null;
		$salt = null;
		
		if( !isset($registration->authProviderID) )
		{
			$passwordResult = $this->generatePassword($registration->password, $registration->passwordConfirm);
			$password = $passwordResult["password"];
			$passwordConfirm = $passwordResult["passwordConfirm"];
			$salt = $passwordResult["salt"];
		}
		
		$result = $this->authenticationService->register(array_merge(
			$registration->toArray(),
			array(
				"password" => $password,
				"passwordConfirm" => $passwordConfirm,
				"salt" => $salt,
			)
		));
		
		if( !$result->hasError && $registration->beginSession )
		{
			$userID = $result->outputs["userID"];
			$this->initializeSession(new SessionUser(array(
				"userID" => $userID,
				"firstName" => $registration->firstName,
				"lastName" => $registration->lastName,
				"email" => $registration->email,
				"imageUrl" => $registration->imageUrl
			)));
		}
		
		return new AuthResult($result);
	}
	
/* user methods */
	function updateUserInformation($user)
	{
		$result = null;
		
		if(isset($this->user->userID))
		{
			$result = $this->authenticationService->register(array(
				"userID" => $this->user->userID,
				"firstName" => $user->firstName,
				"lastName" => $user->lastName,
				"phone" => $user->phone,
				"phoneExt" => $user->phoneExt
			));
			
			if( !$result->hasError )
			{
				$this->user->firstName = $user->firstName;
				$this->user->lastName = $user->lastName;
				$this->user->phone = $user->phone;
				$this->user->phoneExt = $user->phoneExt;
				$this->updateUser();
			}
		}
		
		return $result;
	}
	
	function changePassword($password, $newPassword, $newPasswordConfirm, $ipAddress)
	{
		$result = $this->authenticationService->getUserAuthenticationInfo(array(
			"userID" => $this->user->userID,
			"email" => $this->user->email
		));
		
		if ( !$result->hasError )
		{
			$authInfo = $result->model;
			$oldPassword = $this->hashPassword($password, $authInfo->salt);
			
			$passwordResult = $this->generatePassword($newPassword, $newPasswordConfirm);
			
			$result = $this->authenticationService->changePassword(array(
				"userID" => $this->user->userID,
				"password" => $oldPassword,
				"newPassword" => $passwordResult["password"],
				"newPasswordConfirm" => $passwordResult["passwordConfirm"],
				"salt" => $passwordResult["salt"]
			));
		}
		
		return new AuthResult($result);
	}
	
	function changeEmail($email)
	{
		$result = $this->authenticationService->changeEmail(array(
			"userID" => $this->user->userID,
			"email" => $email,
		));
		
		if(!$result->hasError){
			$this->user->email = $email;
		}
		
		return $result;
	}
	
		
	function setPermissions($permissions)
	{
		$this->user->permissions = $permissions;
		$this->updateUser();
	}
	
/*session*/
	private function createAnonymousSession()
	{
		$this->user = new SessionUser();
		$this->user->userID = -1;
		$this->updateUser();
	}
	
	private function updateSessionID()
	{
		session_regenerate_id(true);
		
		$this->user->lastUpdate = time();
		$this->user->requestCount = 0;
		$this->updateUser();
	}
	
	private function initializeSession($user)
	{
		$now = time();
		
		$this->user = $user;
		$this->user->lastUpdate = $now;
		$this->user->lastActivity = $now;
		$this->user->startTime = $now;
		$this->user->requestCount = 1;
		
		if($this->doesSessionExist()) 
		{
			//session_unset();
			//session_destroy();
			//session_start();
		} 
		
		$this->updateUser();
	}
	
	function doesSessionExist()
	{
		$hasSession = false;
		$sid = session_id();
		if(($sid != '') || (isset($_SESSION))) {
			$hasSession = true;
		} 
		
		return $hasSession;
	}
	
	function updateUser()
	{
		$_SESSION[self::USER_SESSION] = $this->user;
	}
	
/*password*/	
	private function generatePassword($password, $passwordConfirm = null)
	{
		$salt = null;
		
		if ( WebUtilities::getInstance()->hasValue($password) )
		{
			$hashOutput = Pbkdf2::createHash($password);
			$hashParts = explode(":", $hashOutput);
			
			$password = $hashParts[Pbkdf2::HASH_INDEX];
			$salt = $hashParts[Pbkdf2::HASH_SALT_INDEX];
			
			if(WebUtilities::getInstance()->hasValue($passwordConfirm))
			{
				$passwordConfirm = $this->hashPassword($passwordConfirm, $salt);
			}
		}
		else
		{
			$password = null;
		}
		
		return array(
			"password" => $password,
			"passwordConfirm" => $passwordConfirm,
			"salt" => $salt
		);
	}
	
	private function hashPassword($password, $salt)
	{
		$hashOutput = Pbkdf2::doHash($salt, $password);
		$hashParts = explode(":", $hashOutput);
		return $hashParts[Pbkdf2::HASH_INDEX];
	}
	
}
?>
<?php
namespace Rocks\Security;

use Scotch\System as System;
use Scotch\Utilities\WebUtilities as WebUtilities;
use Rocks\Security\AuthResult as AuthResult;

class AccountManager
{	
	const ACCOUNT_SESSION = "rocks.account";
	
	public $account;
	
	private $authenticationService;
	
	function __construct($session)
	{
		$this->authenticationService = System::$application->getService("IAuthenticationService");
		$this->account = WebUtilities::getInstance()->getValue($_SESSION, self::ACCOUNT_SESSION);
	}

/* accounts */
	public function authenticateAccount($userID, $accountID)
	{
		$result = $this->authenticationService->authenticateAccount(array(
			"userID" => $userID,
			"accountID" => $accountID,
		));
		if ( !$result->hasError )
		{
			$this->setAccountSession($result->model);
		}
		return new AuthResult($result);
	}
	
	public function setAccountSession($account)
	{
		$_SESSION[self::ACCOUNT_SESSION] = $account;
	}
	
	public function getSessionAccount()
	{
		$account = null;
		if ( isset($_SESSION[self::ACCOUNT_SESSION]) )
		{
			$account = $_SESSION[self::ACCOUNT_SESSION];
		}
		return $account;
	}
	
}
?>
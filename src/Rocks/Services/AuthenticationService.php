<?php
namespace Rocks\Services;

use Rocks\RocksService as RocksService;
use Rocks\Models\ServiceResult as ServiceResult;
use Rocks\Security\AuthInfo as AuthInfo;
use Rocks\Models\SessionUser as SessionUser;
use Rocks\Models\SessionAccount as SessionAccount;

class AuthenticationService extends RocksService
{
/* CACHE KEYS */

/* CLASS CONSTANTS */
	const SESSION_ACCOUNT_COLLECTION_CLASS = "Rocks\Account\Collections\AccountCollection";
	const SESSION_ACCOUNT_MODEL_CLASS = "Rocks\Models\SessionAccount";
	const SESSION_ACCOUNT_ID_PARAMETER = "accountID";
	
	const SESSION_USER_COLLECTION_CLASS = "Rocks\Account\Collections\UserCollection";
	const SESSION_USER_MODEL_CLASS = "Rocks\Models\SessionUser";
	const SESSION_USER_ID_PARAMETER = "userID";
	
/* PRIVATE PROPERTIES */
	private $sqlSession;
	private $accountRepository;
	
/* CONSTRUCTOR */
	function __construct()
	{
		parent::__construct();
		$this->accountRepository = $this->getService("IAccountRepository");
	}

/* AUTHENTICATION */
	public function getUserAuthenticationInfo($parameters = array())
	{
		$output = $this->accountRepository->getUserAuthenticationInfo($parameters);
		
		if( !$output["outputs"]["@error"] )
		{
			if( $row = $output["data"]->getNextRow() )
			{
				$output["model"] = new AuthInfo($row);
			}
		}
		
		return new ServiceResult($output);
	}
	
	public function authenticateUser($authInfo)
	{
		$user = null;
		$result = $this->callModifyRepository($authInfo,function($source,$parameters) use (&$user){
			$output = $source->authenticateUser($parameters);
			if( !$output["outputs"]["@error"] )
			{
				if( $row = $output["data"]->getNextRow() )
				{
					$user = new SessionUser($row);
				}
			}
			return $output;
		},$this->accountRepository);
		$result->model = $user;
		
		return $result;
	}
	
	public function authenticateAccount($authInfo)
	{
		$account = null;
		
		$result = $this->callModifyRepository($authInfo,function($source,$parameters) use (&$account){
			$output = $source->authenticateAccount($parameters);
			if( !$output["outputs"]["@error"] )
			{
				if( $row = $output["data"]->getNextRow() )
				{
					$account = new SessionAccount($row);
				}
			}
			return $output;
		},$this->accountRepository);
		$result->model = $account;
		
		return $result;
	}

/* REGISTER */
	public function register($user)
	{
		return $this->updateUser($user);
	}

/* PASSWORD */
	public function forgotPassword($user)
	{
		return $this->callModifyRepository($user,function($source,$parameters){
			return $source->updateUser_Forgot($parameters);
		},$this->accountRepository);
	}
	
	public function reset($parameters)
	{
		return $this->callModifyRepository($parameters,function($source,$parameters){
			return $source->updateUser_Recovery($parameters);
		},$this->accountRepository);
	}

/* USER */
	public function updateUser($user)
	{
		return $this->callModifyRepository($user,function($source,$parameters){
			return $source->updateUser($parameters);
		},$this->accountRepository);
	}
	
	public function changePassword($parameters)
	{
		return $this->callModifyRepository($parameters,function($source,$parameters){
			return $source->updateUser_Password($parameters);
		},$this->accountRepository);
	}
	
	public function changeEmail($parameters)
	{
		return $this->callModifyRepository($parameters,function($source,$parameters){
			return $source->updateUser_Email($parameters);
		},$this->accountRepository);
	}
}
?>
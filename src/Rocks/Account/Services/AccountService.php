<?php
namespace Rocks\Account\Services;

use Rocks\RocksService as RocksService;

class AccountService extends RocksService
{
/* CACHE KEYS */

/* CLASS CONSTANTS */
	const ACCOUNT_COLLECTION_CLASS = "Rocks\Account\Collections\AccountCollection";
	const ACCOUNT_MODEL_CLASS = "Rocks\Account\Models\Account";
	const ACCOUNT_ID_PARAMETER = "accountID";
	const ACCOUNT_KEY_PARAMETER = "accountKey";
	
	const CONTACT_COLLECTION_CLASS = "Rocks\Account\Collections\ContactCollection";
	const CONTACT_MODEL_CLASS = "Rocks\Account\Models\Contact";
	const CONTACT_ID_PARAMETER = "contactID";
	
	const USER_COLLECTION_CLASS = "Rocks\Account\Collections\UserCollection";
	const USER_MODEL_CLASS = "Rocks\Account\Models\User";
	const USER_ID_PARAMETER = "userID";
	
	const ALERT_COLLECTION_CLASS = "Rocks\Account\Collections\AlertCollection";
	const ALERT_MODEL_CLASS = "Rocks\Account\Models\Alert";
	const ALERT_ID_PARAMETER = "alertID";
	
	const PACKAGE_COLLECTION_CLASS = "Rocks\Account\Collections\PackageCollection";
	const PACKAGE_MODEL_CLASS = "Rocks\Account\Models\Package";
	const PACKAGE_ID_PARAMETER = "packageID";
	
	const PASSWORD_RECOVERY_COLLECTION_CLASS = "Rocks\Account\Collections\PassworyRecoveryCollection";
	const PASSWORD_RECOVERY_MODEL_CLASS = "Rocks\Account\Models\PasswordRecovery";
	const PASSWORD_RECOVERY_ID_PARAMETER = "userID";
	
	const PERMISSION_COLLECTION_CLASS = "Rocks\Account\Collections\PermissionCollection";
	const PERMISSION_MODEL_CLASS = "Rocks\Account\Models\Permission";
	const PERMISSION_ID_PARAMETER = "permissionID";

/* PRIVATE PROPERTIES */
	private $sqlSession;
	private $accountRepository;
	
	function __construct($sqlSession)
	{
		parent::__construct();
		
		$this->sqlSession = $sqlSession;
		$this->accountRepository = $this->getService("IAccountRepository", $sqlSession);
	}
	
/* ACCOUNTS */
	function getAccounts($parameters = array())
	{
		return $this->callReadRepository(self::ACCOUNT_COLLECTION_CLASS,self::ACCOUNT_MODEL_CLASS,self::ACCOUNT_ID_PARAMETER,function($source,$parameters){
			return $source->getAccounts($parameters);
		},$this->accountRepository,$parameters);
	}
	
	function getAccountTypeahead($parameters = array(), $valueParameter = "accountID", $textParameter = "accountName")
	{
		$data = null;
		if ( $this->util->hasValue($this->util->getValue($parameters, "search")) )
		{
			return $this->callReadRepositoryForTypeahead($valueParameter,$textParameter,function($source,$parameters){
				return $source->getAccounts($parameters);
			},$this->accountRepository,$parameters);
		}
		return $data;
	}
	
	function getAccountByUrl($parameters = array())
	{
		$account = null;
		
		$accountsCollection = $this->callReadRepository(self::ACCOUNT_COLLECTION_CLASS,self::ACCOUNT_MODEL_CLASS,self::ACCOUNT_ID_PARAMETER,function($source,$parameters){
			return $source->getAccounts_Url($parameters);
		},$this->accountRepository,$parameters);
		
		if ($accountsCollection->count == 1)
		{
			$account = $accountsCollection->items[0];
		}
		
		return $account;
	}
	
	function updateAccount($account)
	{
		return $this->callModifyRepository($account,function($source,$parameters){
			return $source->updateAccount($parameters);
		},$this->accountRepository);
	}
	
	function updateAccountSetActive($account)
	{
		return $this->callModifyRepository($account,function($source,$parameters){
			return $source->updateAccount_Active($parameters);
		},$this->accountRepository);
	}
	
	function updateAccountLogo($account)
	{
		return $this->callModifyRepository($account,function($source,$parameters){
			return $source->updateAccount_Logo($parameters);
		},$this->accountRepository);
	}
	
/* BILLING */
	function getBilling($parameters = array())
	{
		return $this->callReadRepository(self::ACCOUNT_COLLECTION_CLASS,self::ACCOUNT_MODEL_CLASS,self::ACCOUNT_ID_PARAMETER,function($source,$parameters){
			return $source->getBilling($parameters);
		},$this->accountRepository,$parameters);
	}
	
	function updateBilling($account)
	{
		return $this->callModifyRepository($account,function($source,$parameters){
			return $source->updateBilling($parameters);
		},$this->accountRepository, null);
	}
	
/* CONTACTS */
	function getContacts($parameters = array())
	{
		return $this->callReadRepository(self::CONTACT_COLLECTION_CLASS,self::CONTACT_MODEL_CLASS,self::CONTACT_ID_PARAMETER,function($repository,$parameters){
			return  $repository->getContacts($parameters);
		},$this->accountRepository,$parameters);		
	}
	
	function updateContact($contact)
	{
		return $this->callModifyRepository($contact,function($repository,$parameters){
			return $repository->updateContact($parameters);
		}, $this->accountRepository);
	}
	
	function deleteContact($contact)
	{
		return $this->callModifyRepository($contact,function($repository,$parameters){
			return $repository->deleteContact($parameters);
		}, $this->accountRepository);
	}
	
/* ALERTS */ 
	function getAlerts($parameters = array())
	{
		return $this->callReadRepository(self::ALERT_COLLECTION_CLASS,self::ALERT_MODEL_CLASS,self::ALERT_ID_PARAMETER,function($repository,$parameters){
			return  $repository->getAlerts($parameters);
		},$this->accountRepository,$parameters);		
	}
	
	function updateAlert($alert)
	{
		return $this->callModifyRepository($alert,function($repository,$parameters){
			return $repository->updateAlert($parameters);
		}, $this->accountRepository);
	}
	
	function deleteAlert($alert)
	{
		return $this->callModifyRepository($alert,function($repository,$parameters){
			return $repository->deleteAlert($parameters);
		}, $this->accountRepository);
	}
	
/* USERS */
	function getUsers($parameters = array())
	{
		return $this->callReadRepository(self::USER_COLLECTION_CLASS,self::USER_MODEL_CLASS,self::USER_ID_PARAMETER,function($source,$parameters){
			return $source->getUsers($parameters);
		},$this->accountRepository,$parameters);
	}
	
	function getUserTypeahead($parameters = array(), $valueParameter = "userID", $textParameter = null)
	{
		$data = null;
		if ( $this->util->hasValue($this->util->getValue($parameters, "search")) )
		{
			if ( !isset($textParameter) )
			{
				$textParameter = function($row){
					return $row["firstName"]." ".$row["lastName"];
					//." (".$row["accountID"].")";
				};
			}
			
			$data = $this->callReadRepositoryForTypeahead($valueParameter,$textParameter,function($source,$parameters){
				return $source->getUsers($parameters);
			},$this->accountRepository,$parameters);
		}
		return $data;
	}
	
	function updateAccountUser($accountUser)
	{
		return $this->callModifyRepository($accountUser,function($source,$parameters){
			return $source->updateAccountUser($parameters);
		},$this->accountRepository,null);
	}
	
	function deleteAccountUser($accountUser)
	{
		return $this->callModifyRepository($accountUser,function($source,$parameters){
			return $source->deleteAccountUser($parameters);
		},$this->accountRepository,null);
	}
	
/* PACKAGES */
	function getPackages($parameters = array())
	{
		return $this->callReadRepository(self::PACKAGE_COLLECTION_CLASS,self::PACKAGE_MODEL_CLASS,self::PACKAGE_ID_PARAMETER,function($source,$parameters){
			return $source->getPackages($parameters);
		},$this->accountRepository,$parameters);
	}
	
	function updatePackage($package)
	{
		return $this->callModifyRepository($package,function($source,$parameters){
			return $source->updatePackage($parameters);
		},$this->accountRepository,null);
	}
	
	function deletePackage($package)
	{
		return $this->callModifyRepository($package,function($source,$parameters){
			return $source->deletePackage($parameters);
		},$this->accountRepository,null);
	}
	
/* ACCOUNT USERS */
	function getAccountUsers($parameters = array())
	{
		return $this->callReadRepository(self::USER_COLLECTION_CLASS,self::USER_MODEL_CLASS,self::USER_ID_PARAMETER,function($source,$parameters){
			return $source->getAccountUsers($parameters);
		},$this->accountRepository,$parameters);
	}
	
	function getUserAccounts($parameters = array())
	{
		return $this->callReadRepository(self::ACCOUNT_COLLECTION_CLASS,self::ACCOUNT_MODEL_CLASS,self::ACCOUNT_ID_PARAMETER,function($source,$parameters){
			return $source->getUserAccounts($parameters);
		},$this->accountRepository,$parameters);
	}

/* PASSWORD RECOVERY */
	function getPasswordRecovery($parameters = array())
	{
		return $this->callReadRepository(self::PASSWORD_RECOVERY_COLLECTION_CLASS,self::PASSWORD_RECOVERY_MODEL_CLASS,self::PASSWORD_RECOVERY_ID_PARAMETER,function($source,$parameters){
			return $source->getPasswordRecovery($parameters);
		},$this->accountRepository,$parameters);
	}
	
/* PERMISSIONS */
	function getPermissions($parameters = array())
	{
		return $this->callReadRepository(self::PERMISSION_COLLECTION_CLASS,self::PERMISSION_MODEL_CLASS,self::PERMISSION_ID_PARAMETER,function($source,$parameters){
			return $source->getPermissions($parameters);
		},$this->accountRepository,$parameters);
	}
	
/* ACCOUNT USER PERMISSIONS */
	function updateAccountUserPermissions($accountUser)
	{
		return $this->callModifyRepository($accountUser,function($source,$parameters){
			return $source->updateAccountUser_Permissions($parameters);
		},$this->accountRepository,null);
	}
}
?>
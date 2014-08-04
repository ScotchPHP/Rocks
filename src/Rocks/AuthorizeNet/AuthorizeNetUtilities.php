<?php
namespace Rocks\AuthorizeNet;

use Scotch\ECommerce\BankAccountTypes as BankAccountTypes;

class AuthorizeNetUtilities
{	
	const CHECKING_ACCOUNT = "CHECKING";
	const BUSINESS_CHECKING_ACCOUNT = "BUSINESSCHECKING";
	const SAVINGS = "SAVINGS";
	
	protected $bankAccountTypeMap = array(
		BankAccountTypes::Checking => self::CHECKING_ACCOUNT,
		BankAccountTypes::BusinessChecking => self::BUSINESS_CHECKING_ACCOUNT,
		BankAccountTypes::Savings => self::SAVINGS
	);

	private static $singletonInstance;
	
	private function __construct()
	{
	}
	
	public static function getInstance()
	{
		if(!self::$singletonInstance)
		{
			self::$singletonInstance = new AuthorizeNetUtilities();
		}
		
		return self::$singletonInstance;
	}
	
	public function getBankAccountType($bankAccountTypeID)
	{
		$bankAccountType = null;
		if(isset($this->bankAccountTypeMap[$bankAccountTypeID]))
		{
			$bankAccountType = $this->bankAccountTypeMap[$bankAccountTypeID];
		}
		return $bankAccountType;
	}
}

?>
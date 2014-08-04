<?php
namespace Rocks\Finance;

use Scotch\ECommerce\BankAccountTypes as ScotchBankAccountTypes;
use Scotch\Exceptions\NotImplementedException as NotImplementedException;

class BankAccountTypes
{
	const Checking = 1;
	const BusinessChecking = 2;
	const Savings = 3;
	
	public static function toScotchBankAccountType($bankAccountType)
	{
		$value = null;
		
		switch($bankAccountType)
		{
			case self::Checking:
				$value = ScotchBankAccountTypes::Checking;
				break;
			case self::BusinessChecking:
				$value = ScotchBankAccountTypes::BusinessChecking;
				break;
			case self::Savings:
				$value = ScotchBankAccountTypes::Savings;
				break;
			default:
				throw new NotImplementedException("500 - Case Not Implemented");
				break;
		}
		
		return $value;
	}
}
?>
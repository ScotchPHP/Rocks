<?php
namespace Rocks\Finance;

use Scotch\ECommerce\PaymentTypes as ScotchPaymentTypes;
use Scotch\Exceptions\NotImplementedException as NotImplementedException;

class PaymentTypes
{
	const Check = 1;
	const CreditCard = 2;
	const ECheck = 3;
	
	public static function fromScotchPaymentType($paymentType)
	{
		$value = null;
		
		switch($paymentType)
		{
			case ScotchPaymentTypes::CreditCard:
				$value = self::CreditCard;
				break;
			case ScotchPaymentTypes::ECheck:
				$value = self::ECheck;
				break;
			default:
				throw new NotImplementedException("500 - Case Not Implemented");
				break;
		}
		
		return $value;
	}
	
	public static function toScotchPaymentType($paymentType)
	{
		$value = null;
		
		switch($paymentType)
		{
			case self::CreditCard:
				$value = ScotchPaymentTypes::CreditCard;
				break;
			case self::ECheck:
				$value = ScotchPaymentTypes::ECheck;
				break;
			default:
				throw new NotImplementedException("500 - Case Not Implemented");
				break;
		}
		
		return $value;
	}
	
}
?>
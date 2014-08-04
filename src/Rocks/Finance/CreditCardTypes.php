<?php
namespace Rocks\Finance;

class CreditCardTypes
{
	const Visa = 1;
	const MasterCard = 2;
	const AmericanExpress = 3;
	const Discover = 4;
	
	public static function getTypeFromNumber($creditCardNumber)
	{
		$creditCardTypeID = null;
		
		if ( isset($creditCardNumber) )
		{
			if( preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/", $creditCardNumber) )
			{
				$creditCardTypeID = self::Visa;
			}
			else if ( preg_match("/^5[0-9]{15}$/", $creditCardNumber) )
			{
				$creditCardTypeID = self::MasterCard;
			}
			else if ( preg_match("/^3[0-9]{14}$/", $creditCardNumber) )
			{
				$creditCardTypeID = self::AmericanExpress;
			}
			else if ( preg_match("/^6[0-9]{15}$/", $creditCardNumber) )
			{
				$creditCardTypeID = self::Discover;
			}
		}
		
		return $creditCardTypeID;
	}
}
?>
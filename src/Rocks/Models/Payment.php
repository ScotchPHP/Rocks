<?php
namespace Rocks\Models;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\ECommerce\CreditCard as CreditCard;
use Scotch\ECommerce\ECheck as ECheck;
use Scotch\ECommerce\BankAccountTypes as ScotchBankAccountTypes;
use Rocks\Finance\PaymentTypes as PaymentTypes;
use Rocks\Finance\BankAccountTypes as BankAccountTypes;
use Rocks\Finance\CreditCardTypes as CreditCardTypes;

class Payment extends BaseModel
{
	public $paymentTypeID;
	
// Credit Card
	public $nameOnCard;
	public $creditCardNumber;
	public $creditCardExpirationMonth;
	public $creditCardExpirationYear;
	public $creditCardCode;
	
// TODO : Address Info

// ECheck
	public $nameOnAccount;
	public $bankName;
	public $bankAccountTypeID;
	public $routingNumber;
	public $accountNumber;
	public $accountNumberConfirm;
	
	public function toPaymentMethod()
	{
		$paymentMethod = null;
		
		switch($this->paymentTypeID)
		{
			case PaymentTypes::CreditCard : 
				$paymentMethod = new CreditCard(array(
					"nameOnCard" => $this->nameOnCard,
					"creditCardNumber" => $this->creditCardNumber,
					"creditCardExpirationMonth" => $this->creditCardExpirationMonth,
					"creditCardExpirationYear" => $this->creditCardExpirationYear,
					"creditCardCode" => $this->creditCardCode
				));
				break;
			case PaymentTypes::ECheck : 
				$paymentMethod = new ECheck(array(
					"nameOnAccount" => $this->nameOnAccount,
					"bankName" => $this->bankName,
					"bankAccountType" => $this->getBankAccountType(),
	 				"routingNumber" => $this->routingNumber,
					"accountNumber" => $this->accountNumber,
					"accountNumberConfirm" => $this->accountNumberConfirm
				));
				break;
			default:
				throw new InvalidTypeException("Payment Type " . $this->paymentTypeID . " does not exist");
				break;
		}
	
		return $paymentMethod;
	}
	
	public function getAccountTypeID()
	{
		$accountType = null;
		switch($this->paymentTypeID)
		{
			case PaymentTypes::CreditCard :
				$accountType = $this->getCreditCardTypeID();
				break;
			case PaymentTypes::ECheck :
				$accountType = $this->bankAccountTypeID;
				break;
			default:
				throw new InvalidTypeException("Payment Type does not exist");
				break;
		}
		return $accounType;
	}
	
	public function getCreditCardTypeID()
	{
		$creditCardTypeID = null;
		
		if($this->paymentTypeID == PaymentTypes::CreditCard)
		{
			$creditCardTypeID = CreditCardTypes::getTypeFromNumber($this->creditCardNumber);
		}
		
		return $creditCardTypeID;
	}
	
	/* convert core bankAccountTypeID to scotchBankAccountType */
	protected function getBankAccountType()
	{
		$bankAccountType = null;
		
		switch($this->bankAccountTypeID)
		{
			case BankAccountTypes::Checking : 
				$bankAccountType = ScotchBankAccountTypes::Checking;
				break;
			case BankAccountTypes::BusinessChecking : 
				$bankAccountType = ScotchBankAccountTypes::BusinessChecking;
				break;
			case BankAccountTypes::Savings : 
				$bankAccountType = ScotchBankAccountTypes::Savings;
				break;
			default: 
				// Invlaid bank account type
				break;
		}
		
		return $bankAccountType;
	}
	
	protected function getTypeMap()
	{
		return array(
			"paymentTypeID" => "int",
			"bankAccountTypeID" => "int",
		);
	}
}
?>
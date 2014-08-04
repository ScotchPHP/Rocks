<?php
namespace Rocks\Account\Models;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\Utilities\Utilities as Utilities;
use Scotch\Encryption\AesEncryptionProvider as AesEncryptionProvider;
use Scotch\DataTypes as DataTypes;

use Rocks\Finance\PaymentTypes as PaymentTypes;

class PaymentMethod extends BaseModel
{	
	public $paymentMethodID;
	public $accountID;
	public $paymentTypeID;
	public $paymentTypeCode;
	public $paymentMethodName;
	public $creditCardTypeID;
	public $creditCardTypeCode;
	public $creditCardNameSalt;
	public $creditCardName;
	public $creditCardNumberSalt;
	public $creditCardNumber;
	public $creditCardNumberTruncated;
	public $creditCardExpirationSalt;
	public $creditCardExpiration;
	public $paymentCountryID;
	public $paymentAddress;
	public $paymentCity;
	public $paymentStateID;
	public $paymentPostalCode;
	public $echeckRoutingNumberSalt;
	public $echeckRoutingNumber;
	public $echeckAccountNumberSalt;
	public $echeckAccountNumber;
	public $bankAccountTypeID;
	public $echeckNameSalt;
	public $echeckName;
	public $echeckBankNameSalt;
	public $echeckBankName;
	public $isPrimary;

	protected function getTypeMap()
	{
		return array(
			"paymentMethodID" => DataTypes::Int,
			"accountID" => DataTypes::Int,
			"paymentTypeID" => DataTypes::Int,
			"creditCardTypeID" => DataTypes::Int,
			"bankAccountTypeID" => DataTypes::Int,
			"isPrimary" => DataTypes::Bit,
		);
	}
	
	public function encrypt($encryptionKey)
	{
		$h = Utilities::getInstance();
		
		$encryptionProvider = new AesEncryptionProvider();
		
		if($this->paymentTypeID == PaymentTypes::CreditCard)
		{
			if($h->hasValue($this->creditCardName))
			{
				$encryptionProvider->generateAndSetIV();
				$this->creditCardNumberTruncated = $h->right($this->creditCardNumber, 4);
				$this->creditCardNameSalt = $encryptionProvider->iv;
				$this->creditCardName = $encryptionProvider->encrypt($this->creditCardName, $encryptionKey);
			}
			
			if($h->hasValue($this->creditCardNumber))
			{
				$encryptionProvider->generateAndSetIV();
				$this->creditCardNumberSalt = $encryptionProvider->iv;
				$this->creditCardNumber = $encryptionProvider->encrypt($this->creditCardNumber, $encryptionKey);
			}
			
			if($h->hasValue($this->creditCardExpiration))
			{
				$encryptionProvider->generateAndSetIV();
				$this->creditCardExpirationSalt = $encryptionProvider->iv;
				$this->creditCardExpiration = $encryptionProvider->encrypt($this->creditCardExpiration, $encryptionKey);
			}
			
			$this->echeckRoutingNumberSalt = null;
			$this->echeckRoutingNumber = null;
			$this->echeckAccountNumberSalt = null;
			$this->echeckAccountNumber = null;
			$this->bankAccountTypeID = null;
			$this->echeckNameSalt = null;
			$this->echeckName = null;
			$this->echeckBankNameSalt = null;
			$this->echeckBankName = null;
		}
		elseif ($this->paymentTypeID == PaymentTypes::ECheck)
		{
			$this->creditCardName = null;
			$this->creditCardNumberSalt = null;
			$this->creditCardNumber = null;
			$this->creditExpirationSalt = null;
			$this->creditExpiration = null;
			
			if($h->hasValue($this->echeckRoutingNumber))
			{			
				$encryptionProvider->generateAndSetIV();
				$this->echeckRoutingNumberSalt = $encryptionProvider->iv;
				$this->echeckRoutingNumber = $encryptionProvider->encrypt($this->echeckRoutingNumber, $encryptionKey);
			}
			
			if($h->hasValue($this->echeckAccountNumber))
			{
				$encryptionProvider->generateAndSetIV();
				$this->echeckAccountNumberSalt = $encryptionProvider->iv;
				$this->echeckAccountNumber = $encryptionProvider->encrypt($this->echeckAccountNumber, $encryptionKey);
			}
			
			if($h->hasValue($this->echeckName))
			{
				$encryptionProvider->generateAndSetIV();
				$this->echeckNameSalt = $encryptionProvider->iv;
				$this->echeckName = $encryptionProvider->encrypt($this->echeckName, $encryptionKey);
			}
			
			if($h->hasValue($this->echeckBankName))
			{
				$encryptionProvider->generateAndSetIV();
				$this->echeckBankNameSalt = $encryptionProvider->iv;
				$this->echeckBankName = $encryptionProvider->encrypt($this->echeckName, $encryptionKey);
			}
		}
	}
	
	public function decrypt($encryptionKey)
	{
		$h = Utilities::getInstance();
		
		$encryptionProvider = new AesEncryptionProvider();
		
		if($this->paymentTypeID == PaymentTypes::CreditCard)
		{
			if($h->hasValue($this->creditCardName))
			{
				$encryptionProvider->iv = $this->creditCardNameSalt;
				$this->creditCardName = $encryptionProvider->decrypt($this->creditCardName, $encryptionKey);
			}
			
			if($h->hasValue($this->creditCardNumber))
			{
				$encryptionProvider->iv = $this->creditCardNumberSalt;
				$this->creditCardNumber = $encryptionProvider->decrypt($this->creditCardNumber, $encryptionKey);
			}
			
			if($h->hasValue($this->creditCardExpiration))
			{
				$encryptionProvider->iv = $this->creditCardExpirationSalt;
				$this->creditCardExpiration = $encryptionProvider->decrypt($this->creditCardExpiration, $encryptionKey);
			}
			
			$this->echeckRoutingNumberSalt = null;
			$this->echeckRoutingNumber = null;
			$this->echeckAccountNumberSalt = null;
			$this->echeckAccountNumber = null;
			$this->bankAccountTypeID = null;
			$this->echeckNameSalt = null;
			$this->echeckName = null;
			$this->echeckBankNameSalt = null;
			$this->echeckBankName = null;
		}
		elseif ($this->paymentTypeID == PaymentTypes::ECheck)
		{
			$this->creditCardName = null;
			$this->creditCardNumberSalt = null;
			$this->creditCardNumber = null;
			$this->creditExpirationSalt = null;
			$this->creditExpiration = null;
			
			if($h->hasValue($this->echeckRoutingNumber))
			{			
				$encryptionProvider->iv = $this->echeckRoutingNumberSalt;
				$this->echeckRoutingNumber = $encryptionProvider->decrypt($this->echeckRoutingNumber, $encryptionKey);
			}
			
			if($h->hasValue($this->echeckAccountNumber))
			{
				$encryptionProvider->iv = $this->echeckAccountNumberSalt;
				$this->echeckAccountNumber = $encryptionProvider->decrypt($this->echeckAccountNumber, $encryptionKey);
			}
			
			if($h->hasValue($this->echeckName))
			{
				$encryptionProvider->iv = $this->echeckNameSalt;
				$this->echeckName = $encryptionProvider->decrypt($this->echeckName, $encryptionKey);
			}
			
			if($h->hasValue($this->echeckBankName))
			{
				$encryptionProvider->iv = $this->echeckBankNameSalt;
				$this->echeckBankName = $encryptionProvider->decrypt($this->echeckName, $encryptionKey);
			}
		}
	}

}
?>

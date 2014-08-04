<?php
namespace Rocks\AuthorizeNet;

use Scotch\ECommerce\PaymentProvider as PaymentProvider;
use Scotch\ECommerce\PaymentTransactionResult as PaymentTransactionResult;
use Scotch\Exceptions\InvalidArgumentException as InvalidArgumentException;
use Scotch\System as System;
use Scotch\Utilities\Utilities as Utilities;
use Scotch\ServerVariables as ServerVariables;

use Rocks\AuthorizeNet\SDK\AuthorizeNetAIM as AuthorizeNetAIM;

class AuthorizeNetPaymentProvider extends PaymentProvider
{		
/* CONSTANTS */
	const PAYMENT_GATEWAY_ID = 1;
	
	const LOGIN_SETTING = "login";
	const TRANSACTION_KEY_SETTING = "transactionKey";
	const TEST_MODE_SETTING = "testMode";
	const SANDBOX_MODE_SETTING = "sandboxMode";
	
	const DEFAULT_EMAIL_CUSTOMER_SETTING = "0";
	const DEFAULT_DUPLICATE_DELAY_SETTING = "5";
	
	const PAYMENT_GATEWAY_SETTINGS = "paymentGatewaySettings";
	const DEFAULT_AUTHNET_SETTINGS = "defaultAuthNetSettings";
	
	const EMAIL_CUSTOMER_SETTING = "emailCustomer";
	const DUPLICATE_DELAY_SETTING = "duplicateDelay";
	
	const SANDBOX_ON = "true";
	const TEST_MODE_ON = "true";
	
/* STATICS */
	protected static $cardDeclinedErrorCodes = array(2,3,4,41,127,141,145,251);
	protected static $cardTransactionErrorCodes = array(5,6,7,8,27,37,44,45,65,78,165);
	protected static $bankAccountDeclinedErrorCodes = array(128);
	protected static $bankAccountTransactionErrorCodes = array(9,10,71,101,104,105,106,107,108,109,110,248);
	protected static $duplicateTransactionErrorCodes = array(11);
	protected static $transactionErrorCodes = array(5,12,15,16,47,49,50,51,52,54,55,72,73,74,75,76,77,79,80,152);
	protected static $merchantErrorCodes = array(17,18,24,28,29,30,31,34,35,36,38,43,46,48,56,66,);
	protected static $authNetErrorCodes = array(19,20,21,22,23,57,58,59,60,61,62,63);
	protected static $authenticationErrorCodes = array(13,123);
	protected static $systemErrorCodes = array(5,40,47,53,68,69,70,81,82,83,84,85,86,87,88,89,90,91,92,97,98,99,100,102,103,116,117,117,119,246,247,250);
	
/* PROPERTIES */

/* PROTECTED PROPERTIES */
	protected $login;
	protected $transactionKey;
	protected $isTestMode = false;
	protected $version;
	protected $emailCustomer;
	protected $duplicateDelay;
	
	function __construct($parameters = array())
	{
		$h = Utilities::getInstance();
		
		$loginSetting = $h->setNull($h->getValue($parameters, self::LOGIN_SETTING));
		$transactionKeySetting = $h->setNull($h->getValue($parameters, self::TRANSACTION_KEY_SETTING));
		$testModeSetting = $h->setBit($h->getValue($parameters, self::TEST_MODE_SETTING));
		$sandboxModeSetting = $h->setBit($h->getValue($parameters, self::SANDBOX_MODE_SETTING));
		$emailCustomerParameter = $h->setNull($h->getValue($parameters, self::EMAIL_CUSTOMER_SETTING));
		$duplicateDelayParameter = $h->setNull($h->getValue($parameters, self::DUPLICATE_DELAY_SETTING));
		
		
		if(!$h->hasValue($loginSetting))
		{
			$loginSetting = $h->setNull(System::$application->configuration->settings[self::LOGIN_SETTING]);
		}
		
		if(!$h->hasValue($transactionKeySetting))
		{
			$transactionKeySetting =  $h->setNull(System::$application->configuration->settings[self::PAYMENT_GATEWAY_SETTINGS][self::DEFAULT_AUTHNET_SETTINGS][self::TRANSACTION_KEY_SETTING]);
		}
		
		if(!$h->hasValue($testModeSetting))
		{
			$testModeSetting = $h->setBit(System::$application->configuration->settings[self::PAYMENT_GATEWAY_SETTINGS][self::DEFAULT_AUTHNET_SETTINGS][self::TEST_MODE_SETTING]);
		}
		
		if(!$h->hasValue($sandboxModeSetting))
		{
			$sandboxModeSetting =  $h->setBit(System::$application->configuration->settings[self::PAYMENT_GATEWAY_SETTINGS][self::DEFAULT_AUTHNET_SETTINGS][self::SANDBOX_MODE_SETTING]);
		}
		
		if(!$h->hasValue($emailCustomerParameter))
		{
			$emailCustomerParameter = $h->setNull($h->getValue(System::$application->configuration->settings[self::PAYMENT_GATEWAY_SETTINGS][self::DEFAULT_AUTHNET_SETTINGS],self::EMAIL_CUSTOMER_SETTING,self::DEFAULT_EMAIL_CUSTOMER_SETTING));
		}
		
		if(!$h->hasValue($duplicateDelayParameter))
		{
			$duplicateDelayParameter = $h->setNull($h->getValue(System::$application->configuration->settings[self::PAYMENT_GATEWAY_SETTINGS][self::DEFAULT_AUTHNET_SETTINGS],self::DUPLICATE_DELAY_SETTING,self::DEFAULT_DUPLICATE_DELAY_SETTING));
		}
		
		$this->login = $loginSetting;
		$this->transactionKey = $transactionKeySetting;
		$this->isTestMode = $testModeSetting;
		$this->emailCustomer = $emailCustomerParameter;
		$this->duplicateDelay = $duplicateDelayParameter;
		
		/*print_r($this);
		die();*/
		
		
		// Set to sandbox mode
		define("AUTHORIZENET_SANDBOX",$sandboxModeSetting);
	}
	
/* CREDIT CARD */
	function chargeCreditCard($paymentTransaction)
	{
		$creditCard = $paymentTransaction->paymentMethod;
		
		$response = $this->sendAuthorizeNetRequest(array(
			"first_name" => $creditCard->getFirstName(),
			"last_name" => $creditCard->getLastName(),
			"card_num" => $creditCard->creditCardNumber,
			"exp_date" => $creditCard->creditCardExpirationMonth . $creditCard->creditCardExpirationYear,
			"card_code" => $creditCard->creditCardCode,
			"amount" => $paymentTransaction->amount,
			"currency_code" => $paymentTransaction->currencyCode,
			
			"invoice_num" => $paymentTransaction->invoiceNumber,
			"description" => $paymentTransaction->description,
			
			"cust_id" => $paymentTransaction->customerID,
			"email" => $paymentTransaction->email,
			"company" => $paymentTransaction->company,
			"phone" => $paymentTransaction->phone,
			"fax" => $paymentTransaction->fax,
			
			"ship_to_country" => $paymentTransaction->shiptToCountry,
			"ship_to_first_name" => $paymentTransaction->shiptToFirstName,
			"ship_to_last_name" => $paymentTransaction->shiptToLastName,
			"ship_to_company" => $paymentTransaction->shiptToCompany,
			"ship_to_address" => $paymentTransaction->shiptToAddress,
			"ship_to_city" => $paymentTransaction->shiptToCity,
			"ship_to_state" => $paymentTransaction->shiptToState,
			"ship_to_zip" => $paymentTransaction->shiptToZip,
			
		),function($authNetAIMRequest, $fields){
			$authNetAIMRequest->setFields($fields);
			return $authNetAIMRequest->authorizeAndCapture();
		});
		
		return $this->generatePaymentTransaction($response);
	}
	
	function authorizeCreditCard($paymentTransaction)
	{
		$creditCard = $paymentTransaction->paymentMethod;
	
		$response = $this->sendAuthorizeNetRequest(array(
			"first_name" => $creditCard->getFirstName(),
			"last_name" => $creditCard->getLastName(),
			"card_num" => $creditCard->creditCardNumber,
			"exp_date" => $creditCard->creditCardExpirationMonth . $creditCard->creditCardExpirationYear,
			"card_code" => $creditCard->creditCardCode,
			"amount" => $paymentTransaction->amount,
			"currency_code" => $paymentTransaction->currencyCode,
			
			"invoice_num" => $paymentTransaction->invoiceNumber,
			"description" => $paymentTransaction->description,
			
			"cust_id" => $paymentTransaction->customerID,
			"email" => $paymentTransaction->email,
			"company" => $paymentTransaction->company,
			"phone" => $paymentTransaction->phone,
			"fax" => $paymentTransaction->fax,
			
			"ship_to_country" => $paymentTransaction->shiptToCountry,
			"ship_to_first_name" => $paymentTransaction->shiptToFirstName,
			"ship_to_last_name" => $paymentTransaction->shiptToLastName,
			"ship_to_company" => $paymentTransaction->shiptToCompany,
			"ship_to_address" => $paymentTransaction->shiptToAddress,
			"ship_to_city" => $paymentTransaction->shiptToCity,
			"ship_to_state" => $paymentTransaction->shiptToState,
			"ship_to_zip" => $paymentTransaction->shiptToZip,
			
		),function($authNetAIMRequest, $fields){
			$authNetAIMRequest->setFields($fields);
			return $authNetAIMRequest->authorizeOnly();
		});
		
		return $this->generatePaymentTransaction($response);
	}
	
	function voidCreditCardTransaction($transactionID)
	{
		$response = $this->sendAuthorizeNetRequest(array(
			"trans_id" => $transactionID,
		),function($authNetAIMRequest, $fields){
			$authNetAIMRequest->setFields($fields);
			return $authNetAIMRequest->void();
		});
		
		return $this->generatePaymentTransaction($response);
	}
	
	function creditCreditCardTransaction($transactionID,$amount,$cardNumber)
	{
		$response = $this->sendAuthorizeNetRequest(array(
			"trans_id" => $transactionID,
			"amount" => $amount,
			"card_num" => $cardNumber,
		),function($authNetAIMRequest, $fields){
			$authNetAIMRequest->setFields($fields);
			return $authNetAIMRequest->credit();
		});
		
		return $this->generatePaymentTransaction($response);
	}

/* ECHECK */
	function chargeACH($paymentTransaction)
	{
		$echeck = $paymentTransaction->paymentMethod;
		
		$bankAccountType = AuthorizeNetUtilities::getInstance()->getBankAccountType($echeck->getAccountType());
		
		$response = $this->sendAuthorizeNetRequest(array(
			"method" => "ECHECK",
			"bank_aba_code" => $echeck->routingNumber,
			"bank_acct_num" => $echeck->accountNumber,
			"bank_acct_type" => $bankAccountType,
			"bank_name" => $echeck->bankName,
			"bank_acct_name" => $echeck->nameOnAccount,
			"echeck_type" => "WEB",
			"bank_check_number" => "",
			"relay_response" => "false",
			"amount" => $paymentTransaction->amount,
			
			"first_name" => $echeck->getFirstName(),
			"last_name" => $echeck->getLastName(),
			
			"invoice_num" => $paymentTransaction->invoiceNumber,
			"description" => $paymentTransaction->description,
			
			"cust_id" => $paymentTransaction->customerID,
			"email" => $paymentTransaction->email,
			"company" => $paymentTransaction->company,
			"phone" => $paymentTransaction->phone,
			"fax" => $paymentTransaction->fax,
			
			"ship_to_country" => $paymentTransaction->shiptToCountry,
			"ship_to_first_name" => $paymentTransaction->shiptToFirstName,
			"ship_to_last_name" => $paymentTransaction->shiptToLastName,
			"ship_to_company" => $paymentTransaction->shiptToCompany,
			"ship_to_address" => $paymentTransaction->shiptToAddress,
			"ship_to_city" => $paymentTransaction->shiptToCity,
			"ship_to_state" => $paymentTransaction->shiptToState,
			"ship_to_zip" => $paymentTransaction->shiptToZip,
		),function($authNetAIMRequest, $fields){
			$authNetAIMRequest->setFields($fields);
			return $authNetAIMRequest->authorizeAndCapture();
		});
		
		return $this->generatePaymentTransaction($response);
	}

/* GATEWAY */
	function getGatewayID()
	{
		return self::PAYMENT_GATEWAY_ID;
	}
	
/* PROTECTED FUNCTIONS */
	protected function sendAuthorizeNetRequest($fields,$transactionAction)
	{
		$authNetAIMRequest = new AuthorizeNetAIM($this->login,$this->transactionKey);
		
		if ($this->isTestMode)
		{
			$fields["test_request"] = "TRUE";
		}
		$fields["email_customer"] = $this->emailCustomer;
		$fields["duplicate_window"] = $this->duplicateDelay;
		$fields["customer_ip"] = $_SERVER[ServerVariables::REMOTE_ADDR];
		
		return $transactionAction($authNetAIMRequest, $fields);
	}
	
	protected function generatePaymentTransaction($authNetResponse)
	{
		if(!isset($authNetResponse))
		{
			throw new InvalidArgumentException("authNetResponse cannot be null");
		}
		
		$transaction = new PaymentTransactionResult();
		$transaction->gatewayID = $this->getGatewayID();
		$transaction->wasApproved = $authNetResponse->approved;
		
		if($transaction->wasApproved)
		{
			$transaction->transactionID = $authNetResponse->transaction_id;
		}
		else
		{
			$errorCode = $authNetResponse->response_reason_code;
			$transaction->errorCode = $errorCode;
			$transaction->errorMessage = $authNetResponse->response_reason_text;
			
			if(in_array($errorCode,self::$cardDeclinedErrorCodes))
			{
				$transaction->errorMessageCode = "errorPaymentProviderCreditCardDeclined";
			}
			else if(in_array($errorCode,self::$cardTransactionErrorCodes))
			{
				$transaction->errorMessageCode = "errorPaymentProviderCreditCardTransaction";
			}
			else if(in_array($errorCode,self::$bankAccountDeclinedErrorCodes))
			{
				$transaction->errorMessageCode = "errorPaymentProviderBankAccountDeclined";
			}
			else if(in_array($errorCode,self::$bankAccountTransactionErrorCodes))
			{
				$transaction->errorMessageCode = "errorPaymentProviderBankAccountTransaction";
			}
			else if(in_array($errorCode,self::$duplicateTransactionErrorCodes))
			{
				$transaction->errorMessageCode = "errorPaymentProviderDuplicationTransaction";
			}
			else if(in_array($errorCode,self::$transactionErrorCodes))
			{
				$transaction->errorMessageCode = "errorPaymentProviderTransaction";
			}
			else if(in_array($errorCode,self::$authNetErrorCodes))
			{
				$transaction->errorMessageCode = "errorPaymentProviderTryAgain";
			}
			else
			{
				$transaction->errorMessageCode = "errorPaymentProviderUnknownError";
			}
		}

		return $transaction;
	}
	
}
?>
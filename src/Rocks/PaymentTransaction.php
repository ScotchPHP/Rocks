<?php
namespace Rocks;

use Scotch\System as System;
use Scotch\ECommerce\PaymentTransaction as ScotchPaymentTransaction;

abstract class PaymentTransaction extends ScotchPaymentTransaction
{
	const PAYMENT_GATEWAY_SETTINGS = "paymentGatewaySettings";
	
	//private $paymentProvider;
	
	function __construct($paymentMethod, $properties = array())
	{
		parent::__construct($paymentMethod, $properties);
	}
	
	public function processCharge($paymentProvider)
	{
		return $this->paymentMethod->processCharge($paymentProvider, $this);
	}
	
	public function processAuthorization($paymentProvider)
	{
		return $this->paymentMethod->processAuthorization($paymentProvider, $this);
	}
	
	public function processVoid($paymentProvider)
	{
		return $this->paymentMethod->processVoid($paymentProvider, $this);
	}
	
	public function processCredit($paymentProvider)
	{
		return $this->paymentMethod->processCredit($paymentProvider, $this);
	}
	
	/*
	private function getPaymentProviderSettings()
	{
		return System::$application->configuration->settings[self::PAYMENT_GATEWAY_SETTINGS][$this->getSettingsKey()];
	}
	
	abstract protected function getSettingsKey();
	*/
}
?>
<?php
namespace Rocks;

use Scotch\Net\MailMessage as MailMessage;
use Scotch\System as System;
use Scotch\Utilities\Utilities as Utilities;

class ErrorMailMessage extends MailMessage
{
	const ERROR_EMAIL_SETTING = "errorEmailAddresses";
	const KOHVA_FROM_EMAIL_ADDRESS = "info@kohva.com";
	const KOHVA_ERROR_SUBJECT = "Error Has Occurred on Kohva";

	function __construct($parameters = array())
	{
		parent::__construct($parameters);
		
		$this->from = self::KOHVA_FROM_EMAIL_ADDRESS;
		$this->subject = self::KOHVA_ERROR_SUBJECT;
		
		$toAddresses = Utilities::getInstance()->getValue(System::$application->configuration->settings, self::KOHVA_FROM_EMAIL_ADDRESS, "");
		
		$toAddresses = explode(";",$toAddresses);
		$this->addTo($toAddresses);
	}
}
?>
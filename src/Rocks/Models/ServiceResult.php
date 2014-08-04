<?php
namespace Rocks\Models;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\Utilities\WebUtilities as WebUtilities;

class ServiceResult extends BaseModel
{
	const ERROR_PARAMETER = "@error";
	
	public $model;
	public $outputs = array();
	public $errors = array();
	public $hasError = false;
	
	protected $util;
	
	public function __construct($properties = array())
	{
		parent::__construct($properties);
		
		$this->util = WebUtilities::getInstance();
		
		$this->parseOutputs($this->outputs);
		$this->parseErrors($this->errors);
	}
	
	protected function parseErrors($errors)
	{
		if($this->hasError)
		{
			$errorList = array();
			foreach($errors as $errorKey => $errorValue)
			{
				if($this->util->hasValue($errorValue))
				{
					$key = trim($errorKey,"@ \t\n\r\0\x0B");
					$errorList[$key] = $this->util->getString($errorValue);
				}
			}
			$this->errors = $errorList;
		}
	}
	
	protected function parseOutputs($outputs)
	{
		$outputList = array();
		foreach($outputs as $outputKey => $outputValue)
		{
			if($outputKey == self::ERROR_PARAMETER)
			{
				$this->hasError = $outputValue;
			}
			else
			{
				if($this->util->hasValue($outputValue))
				{
					$key = trim($outputKey,"@ \t\n\r\0\x0B");
					$outputList[$key] = $outputValue;
				}
			}
		}
		$this->outputs = $outputList;
	}
}
?>
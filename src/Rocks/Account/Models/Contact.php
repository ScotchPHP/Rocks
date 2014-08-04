<?php
namespace Rocks\Account\Models;

use Rocks\Models\Person as Person;
use Scotch\DataTypes as DataTypes;

class Contact extends Person
{	
	public $contactID;
	public $accountID;
	public $contactTypeID;
	public $officePhone;
	public $officePhoneExt;
	public $cellPhone;
	public $homePhone;
	
	protected function getTypeMap()
	{
		return array(
			"contactID" => DataTypes::Int,
			"accountID" => DataTypes::Int,
			"contactTypeID" => DataTypes::Int
		);
	}
}
?>
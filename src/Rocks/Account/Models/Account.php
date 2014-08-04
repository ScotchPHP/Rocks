<?php
namespace Rocks\Account\Models;

use Scotch\DataTypes as DataTypes;
use Scotch\Models\BaseModel as BaseModel;

class Account extends BaseModel
{
	public $accountID;
	public $parentAccountID;
	public $accountKey;
	public $accountUrl;
	public $planID;
	public $isActive;
	public $accountName;
	public $email;
	public $phone;
	public $phoneExt;
	public $countryID;
	public $address;
	public $address2;
	public $city;
	public $stateID;
	public $postalCode;
	public $logoUrl;
	public $logoAttributes;
	public $dateCreated;
	
	public $billToEmail;
	public $billToCountryID;
	public $billToAddress;
	public $billToAddress2;
	public $billToCity;
	public $billToStateID;
	public $billToPostalCode;
	
	public $permissions;
	
	public function phoneWithExtension()
	{
		return $this->phone . ((isset($this->phoneExt)) ? " x" . $this->phoneExt : "");
	}
	
	protected function getTypeMap()
	{
		return array(
			"accountID" => DataTypes::Int,
			"parentAccountID" => DataTypes::Int,
			"planID" => DataTypes::Int,
			"isActive" => DataTypes::Bit,
			"dateCreated" => DataTypes::Date,
			"billingIsActive" => DataTypes::Bit,
			"paymentMethodID" => DataTypes::Int
		);
	}
}
?>
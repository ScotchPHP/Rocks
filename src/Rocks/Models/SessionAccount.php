<?php
namespace Rocks\Models;

use Scotch\DataTypes as DataTypes;
use Scotch\Models\BaseModel as BaseModel;

class SessionAccount extends BaseModel
{
	public $accountID;
	public $parentAccountID;
	public $accountKey;
	public $accountUrl;
	public $planID;
	public $accountName;
	public $email;
	public $logoUrl;
	public $logoAttributes;
	
	protected function getTypeMap()
	{
		return array(
			"accountID" => DataTypes::Int,
			"parentAccountID" => DataTypes::Int,
			"planID" => DataTypes::Int,
		);
	}
}
?>
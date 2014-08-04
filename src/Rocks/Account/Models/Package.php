<?php
namespace Rocks\Account\Models;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\DataTypes as DataTypes;

class Package extends BaseModel
{	
	public $accountID;
	public $price;
	public $isActive;

	protected function getTypeMap()
	{
		return array(
			"accountID" => DataTypes::Int,
			"price" => DataTypes::Float,
			"isActive" => DataTypes::Bit,
		);
	}
}
?>
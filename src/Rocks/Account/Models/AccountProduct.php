<?php
namespace Rocks\Account\Models;

use Rocks\Product\Models\Product as Product;
use Scotch\DataTypes as DataTypes;

class AccountProduct extends Product
{	
	public $accountID;
	public $planID;
	public $planName;
	public $price;
	public $isActive;

	protected function getTypeMap()
	{
		return array(
			"accountID" => DataTypes::Int,
			"productID" => DataTypes::Int,
			"planID" => DataTypes::Int,
			"price" => DataTypes::Float,
			"isActive" => DataTypes::Bit,
		);
	}
}
?>
<?php
namespace Rocks\Product\Models;

use Scotch\Models\BaseModel as BaseModel;

class Plan extends BaseModel
{
	public $planID;
	public $productID;
	public $planName;
	public $price;
	
	protected function getTypeMap()
	{
		return array(
			"planID" => "int",
			"productID" => "int",
			"price" => "float",
		);
	}
}
?>
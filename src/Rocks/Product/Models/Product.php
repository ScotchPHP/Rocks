<?php
namespace Rocks\Product\Models;

use Scotch\Models\BaseModel as BaseModel;

class Product extends BaseModel
{	
	public $productID;
	public $productName;

	protected function getTypeMap()
	{
		return array(
			"productID" => "int",
		);
	}
}
?>
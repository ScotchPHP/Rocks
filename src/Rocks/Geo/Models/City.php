<?php
namespace Rocks\Geo\Models;

use Scotch\Models\BaseModel as BaseModel;

class City extends BaseModel
{	
	public $cityID;
	public $cityName;
	public $countryID;
	public $stateID;
	public $latitude;
	public $longitude;

	protected function getTypeMap()
	{
		return array(
			"cityID" => "int",
			"latitude" => "float",
			"longitude" => "float",
		);
	}

}
?>
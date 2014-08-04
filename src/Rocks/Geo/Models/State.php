<?php
namespace Rocks\Geo\Models;

use Scotch\Models\BaseModel as BaseModel;

class State extends BaseModel
{	
	public $stateID;
	public $countryID;
	public $stateName;
	public $category;
	
	protected function getTypeMap()
	{
		return array(
		);
	}
}
?>
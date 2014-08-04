<?php
namespace Rocks\Models;

use Scotch\DataTypes as DataTypes;
use Scotch\Models\BaseModel as BaseModel;

class TrackModel extends BaseModel
{
	public $dateCreated;
	public $dateUpdated;
	public $createdByUserID;
	public $updatedByUserID;
	protected $createdByUser;
	protected $updatedByUser;
	
	public function getCreatedByUser()
	{
	}
	
	public function getUpdatedByUser()
	{
	}
	
	protected function getTypeMap()
	{
		return array(
			"dateCreated" => DataTypes::Date,
			"dateUpdated" => DataTypes::Date,
			"createdByUserID" => DataTypes::Int,
			"updatedByUserID" => DataTypes::Int,
		);
	}
}
?>
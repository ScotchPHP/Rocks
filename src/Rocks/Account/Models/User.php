<?php
namespace Rocks\Account\Models;

use Scotch\DataTypes as DataTypes;
use Rocks\Models\Person as Person;

class User extends Person
{
	public $userID;
	public $authProviderID;
	public $authID;
	public $isActive;
	public $password;
	public $salt;
	public $imageUrl;
	public $dateCreated;
	
	public $permissions;
	
	public $lastLoginDateStamp;
	
	protected function getTypeMap()
	{
		return array(
			"userID" => DataTypes::Int,
			"imageHeight" => DataTypes::Int,
			"imageWidth" => DataTypes::Int,
			"dateCreated" => DataTypes::Date,
			"permissions" => DataTypes::Int,
			"lastLoginDateStamp" => DataTypes::Date,
		);
	}
}
?>
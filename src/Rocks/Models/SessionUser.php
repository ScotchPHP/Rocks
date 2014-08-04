<?php
namespace Rocks\Models;

use Scotch\Models\BaseModel;
use Scotch\DataTypes as DataTypes;

class SessionUser extends BaseModel
{
	public $userID;
	public $firstName;
	public $lastName;
	public $email;
	public $phone;
	public $phoneExt;
	public $imageUrl;
	public $changePassword;
	public $lastUpdate;
	public $lastActivity;
	public $startTime;
	public $requestCount;
	public $isAdmin;
	public $authProviderID;
	public $authID;
	public $accounts;
	public $permissions;
	
	public function isLoggedIn()
	{
		return ($this->userID > 0) ? true : false;
	}
	
	protected function getTypeMap()
	{
		return array_merge(array(
			"userID" => DataTypes::Int,
			"changePassword" => DataTypes::Bit,
			"lastUpdate" => DataTypes::DateTime,
			"lastActivity" => DataTypes::DateTime,
			"isAdmin" => DataTypes::Bit,
			"authProviderID" => DataTypes::Int,
			"accounts" => DataTypes::Int,
		),parent::getTypeMap());
	}
}
?>
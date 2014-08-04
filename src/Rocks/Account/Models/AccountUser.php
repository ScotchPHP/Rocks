<?php
namespace Rocks\Account\Models;

use Rocks\Account\Models\User as User;
use Scotch\DataTypes as DataTypes;

class AccountUser extends User
{
	public $accountID;
	public $userID;
	
	protected function getTypeMap()
	{
		return array(
			"accountID" => DataTypes::Int,
			"userID" => DataTypes::Int,
		);
	}
}
?>
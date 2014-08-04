<?php
namespace Rocks\Account\Models;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\DataTypes as DataTypes;

class PasswordRecovery extends BaseModel
{
	public $userID;
	public $recoveryKey;
	public $expirationDate;
	public $isExpired;
	
	public function isRecoveryKeyValid($recoveryKey)
	{
		return ($recoveryKey == $this->recoveryKey) ? true : false;
	}
	
	protected function getTypeMap()
	{
		return array(
			"userID" => DataTypes::Int,
			"expirationDate" => DataTypes::DateTime,
			"isExpired" => DataTypes::Bit
		);
	}
}
?>
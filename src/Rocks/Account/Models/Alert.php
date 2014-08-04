<?php
namespace Rocks\Account\Models;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\DataTypes as DataTypes;

class Alert extends BaseModel
{
	public $alertID;
	public $accountID;
	public $email;
	public $alertDate;
	public $alertTitle;
	public $alertDescription;
	
	protected function getTypeMap()
	{
		return array(
			"alertID" => DataTypes::Int,
			"accountID" => DataTypes::Int,
			"alertDate" => DataTypes::Date
		);
	}
}
?>
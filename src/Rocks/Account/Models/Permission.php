<?php
namespace Rocks\Account\Models;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\DataTypes as DataTypes;

class Permission extends BaseModel
{
	public $permissionID;
	public $permissionCode;
	public $permissionValue;
	
	protected function getTypeMap()
	{
		return array(
			"permissionID" => DataTypes::Int,
			"permissionValue" => DataTypes::Int
		);
	}
	
	public static function hasPermission($permission, $userPermissions)
	{
		return ($permission & $userPermissions);
	}
}
?>
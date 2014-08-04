<?
namespace Rocks\Models;

use Rocks\Account\Models\User as User;
use Scotch\DataTypes as DataTypes;

class Registration extends User
{
	public $accountID;
	public $invitationID;
	public $invitationCode;
	public $password;
	public $passwordConfirm;
	public $beginSession;
	
	protected function getTypeMap()
	{
		return array_merge(array(
			"accountID" => DataTypes::Int,
			"invitationID" => DataTypes::Int,
			"beginSession" => DataTypes::Bit,
		),parent::getTypeMap());
	}
}
?>
<?php
namespace Rocks\Account\Models;

use Rocks\Account\Models\User as User;

class UserDetail extends User
{
	public $newEmail;
	public $confirmEmail;
	public $password;
	public $newPassword;
	public $confirmPassword;
	public $recoveryKey;
}
?>
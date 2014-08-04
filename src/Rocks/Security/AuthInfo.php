<?php
namespace Rocks\Security;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\Encryption\Pbkdf2 as Pbkdf2;

class AuthInfo extends BaseModel
{
	public $salt;
}
?>
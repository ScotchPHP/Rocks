<?php
namespace Rocks\Security;

use Scotch\Models\BaseModel as BaseModel;

class AuthResult extends BaseModel
{
	public $hasError;
	public $errors = array();
	public $outputs = array();
}
?>
<?php
namespace Rocks\Models;

use Scotch\Models\BaseModel as BaseModel;

class Person extends BaseModel
{
	public $firstName;
	public $lastName;
	public $email;
	public $phone;
	public $phoneExt;
	
	public function fullName()
	{
		return $this->firstName . " " . $this->lastName;
	}
	
	public function lastNameFirstName()
	{
		return $this->lastName . ", " . $this->firstName;
	}
	
	public function phoneWithExtension()
	{
		return $this->phone . ((isset($this->phoneExt)) ? " Ext: " . $this->phoneExt : "");
	}
}
?>
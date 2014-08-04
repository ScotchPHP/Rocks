<?php
namespace Kohva\Core\Controllers;

use Kohva\Core\Controllers\CoreController as CoreController;

class RegisterController extends CoreController
{
	function __construct()
	{
		parent::__construct();
		
		$this->template = "Kohva\Core\Templates\BasicTemplate";
	}
	
	function index()
	{
		$data = array();
		
		// Send a logged in user back to the homepage
		if($this->user->isLoggedIn())
		{
			$this->webUtil->redirect("/");
		}
		
		$commit = $this->request("commit", "bit");
		
		if($commit == true)
		{
			$email = $this->request("email", "string", $_POST);
			$password = $this->request("password", "string", $_POST);
			$passwordConfirm = $this->request("passwordConfirm", "string", $_POST);
			$firstName = $this->request("firstName", "string", $_POST);
			$lastName = $this->request("lastName", "string", $_POST);
			
			if($password == $passwordConfirm)
			{
				$userInfo = $this->userManager->register($email, $password, $firstName, $lastName);
				
				if($userInfo["outputs"]["@error"])
				{
					$data["user"] = $this->inputs;
					$data["errors"] = $userInfo["errors"];
				}
				else
				{
					$this->webUtil->redirect("/forms/");
				}
			}
			else
			{
				$data["user"] = $this->inputs;
				$data["errors"] = array(
					"@password_Error" => "Passwords must match."
				);
			}
		}
		
		return $this->createView(array(
			"data" => $data,
			"metaTitle" => "Register",
			"file" => "{LIBRARY_ROOT}/Kohva/Core/Views/Users/register.php"
		));
	}
	
	
}
?>
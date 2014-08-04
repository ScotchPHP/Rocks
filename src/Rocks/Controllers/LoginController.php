<?php
namespace Kohva\Core\Controllers;

use Kohva\Core\Controllers\CoreController as CoreController;
use Kohva\Scotch\ServerVariables as ServerVariables;

class LoginController extends CoreController
{

	function __construct()
	{
		parent::__construct();
		
		$this->template = "Kohva\Core\Templates\BasicTemplate";
	}
	
	function index()
	{
		$data = array();
		
		$commit = $this->request("commit", "bit");
		
		if($commit == true)
		{
			$email = $this->request("email", "string", $_POST);
			$password = $this->request("password", "string", $_POST);
			$ipAddress = $_SERVER[ServerVariables::REMOTE_ADDR];
			
			$login = $this->userManager->login($email, $password, $ipAddress);
			$outputParameters = $this->util->getValue($login, "outputs");
			
			$hasError = $this->util->getValue($outputParameters, "@error");
			if($hasError)
			{
				$data["user"] = $this->inputs;
				$data["errors"] = $login["errors"];
			}
			else
			{
				$this->redirect("/forms/");
			}
		}
		
		return $this->createView(array(
			"data" => $data,
			"metaTitle" => "Login",
			"file" => "{LIBRARY_ROOT}/Kohva/Core/Views/Login/login.php"
		));
	
	}
	
	function logout()
	{
		$this->userManager->logout();
		
		$this->webUtil->redirect("/login/");
	}
	
	function forgot()
	{
		$data = array();
		
		$commit = $this->request("commit", "bit");
		
		if($commit == true)
		{
			$email = $this->request("email", "string", $_POST);
			
			$forgot = $this->userManager->forgot($email);
			
			if($forgot["outputs"]["@error"])
			{
				$data["user"] = $this->inputs;
				$data["errors"] = $forgot["errors"];
			}
			else
			{
				$this->webUtil->redirect("/login/?msg=success");
			}
		}
		
		return $this->createView(array(
			"data" => $data,
			"metaTitle" => "Password Recovery",
			"file" => "{LIBRARY_ROOT}/Kohva/Core/Views/Login/forgot.php"
		));
	}
	
	function reset()
	{
		$data = array();
		
		$commit = $this->request("commit", "bit");
		$userID = $this->request("userID", "int");
		$recoveryKey = $this->request("recoveryKey", "string");
			
		if($commit == true)
		{
			
			$email = $this->request("email", "string", $_POST);
			$password = $this->request("password", "string", $_POST);
			$passwordConfirm = $this->request("passwordConfirm", "string", $_POST);
			
			if($password == $passwordConfirm)
			{			
				$reset = $this->userManager->reset($userID, $recoveryKey, $email, $password);
				
				if($reset["outputs"]["@error"])
				{
					if($reset["outputs"]["@recoveryExpired"])
					{
						$reset["errors"]["@recovery_Error"] = "Your recovery key has expired!";
					}
					$data["errors"] = $reset["errors"];
				}
				else
				{
					$this->webUtil->redirect("/login/?msg=success");
				}
			}
			else
			{
				$data["errors"] = array(
					"@password_Error" => "Passwords must match"
				);
			}
			
			
		}
		
		$data["user"] = $this->inputs;
		
		return $this->createView(array(
			"data" => $data,
			"metaTitle" => "Password Recovery",
			"file" => "{LIBRARY_ROOT}/Kohva/Core/Views/Login/reset.php"
		));
	}
	
}
?>
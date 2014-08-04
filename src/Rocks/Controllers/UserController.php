<?php
namespace Kohva\Core\Controllers;

use Kohva\Core\Controllers\CoreController as CoreController;

class UserController extends CoreController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
	}
	
	function update()
	{
	}
	
	function login()
	{
		$data = array();
		
		$commit = $this->request("commit", "bit");
		
		if($commit == true)
		{
			$email = $this->request("email", "string", $_POST);
			$password = $this->request("password", "string", $_POST);
			
			$data = $this->userManager->login($email, $password);
			
			$data = array_merge($this->inputs, $data);
			
			if(!$data["outputs"]["@error"])
			{
				header( 'Location: /userTest3.php' ) ;
			}
		}
		
		return $data;
	}
	
	function register()
	{
		$data = array();
		
		$commit = $this->request("commit", "bit");
		
		if($commit == true)
		{
			echo "Registering User";
			
			$email = $this->request("email", "string", $_POST);
			$password = $this->request("password", "string", $_POST);
			$passwordConfirm = $this->request("passwordConfirm", "string", $_POST);
			$firstName = $this->request("firstName", "string", $_POST);
			$lastName = $this->request("lastName", "string", $_POST);
			$phone = null;
			$phoneExt = null;
			
			$data = $this->userManager->register($email, $password, $passwordConfirm, $firstName, $lastName, $phone, $phoneExt);
			
			
			$data = array_merge($this->inputs, $data);
			
			
		}
		
		
	}
	
	function logout()
	{
		$this->userManager->logout();
		
		header( 'Location: /userTest3.php' ) ;
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
}
?>
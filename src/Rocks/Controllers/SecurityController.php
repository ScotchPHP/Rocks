<?php
namespace Rocks\Controllers;

use Rocks\Controllers\RocksController as RocksController;

class SecurityController extends RocksController
{

	function __construct()
	{
		parent::__construct();
		
		$this->template = "Rocks\Templates\BlankTemplate";
	}
	
	function index()
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
				$this->webUtil->redirect("/forms/");
			}
		}
		
		return $this->createView(array(
			"metaTitle" => "Login",
			"data" => $data,
			"file" => "/security/login.php"
		));
	}
	
	function logout()
	{
		$this->userManager->logout();
		
		$this->webUtil->redirect("/");
		
	}
	
	function forgot()
	{
		return $this->createView(array(
			"metaTitle" => "Login",
			"data" => $data,
			"file" => "/security/fogot.php"
		));
	}
	
	function reset()
	{
		
	}
	
}
?>
<?php
namespace Rocks\Account\Api;

use Rocks\Controllers\RocksApiController as RocksApiController;
use Rocks\Account\Models\Contact as Contact;

class AccountContactsApiController extends RocksApiController
{
	/*private static $methodMap = array(
		"get" => array(
			"index" => "index",
		)
	);*/
	
	protected $accountService;
	
	function __construct($router)
	{
		parent::__construct($router);
			
		$this->accountService = $this->getService("IAccountService", $this->sqlSession);
	}

/* GET */
	function get()
	{
		$data = $this->accountService->getContacts(array(
			"contactID" => $this->request("contactID", "int"),
			"accountID" => $this->request("accountID", "int"),
			"search" => $this->request("search"),
		));		
		
		return $this->createApiView($data);
	}

/* POST */
	function post()
	{
		return $this->updateAccountContact($_POST);
	}

/* PUT */
	function put()
	{
		global $_PUT;
		return $this->updateAccountContact($_PUT);
	}

/* DELETE */
	function delete()
	{
		global $_DELETE;
		$contact = new Contact($_DELETE);
		$data = $this->accountService->deleteContact($contact);
		
		return $this->createApiView($data);
	}

/* METHODS */
	private function updateAccountContact(&$ref)
	{
		$contact = new Contact($ref);
		$data = $this->accountService->updateContact($contact);
		
		return $this->createApiView($data);
	}
}
?>
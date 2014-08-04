<?php
namespace Rocks\Account\Api;

use Rocks\Controllers\RocksApiController as RocksApiController;
use Rocks\Account\Models\AccountUser as AccountUser;

class AccountUsersApiController extends RocksApiController
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
		$data = $this->accountService->getAccountUsers(array(
			"userID" => $this->request("userID", "int"),
			"accountID" => $this->request("accountID", "int"),
			"search" => $this->request("search"),
		));		
		
		return $this->createApiView($data);
	}

/* POST */
	function post()
	{
		return $this->updateAccountUser($_POST);
	}

/* PUT */
	function put()
	{
		global $_PUT;
		return $this->updateAccountUser($_PUT);
	}

/* DELETE */
	function delete()
	{
		global $_DELETE;
		$accountUser = new AccountUser($_DELETE);
		$data = $this->accountService->deleteAccountUser($accountUser);
		
		return $this->createApiView($data);
	}

/* METHODS */
	private function updateAccountUser(&$ref)
	{
		$accountUser = new AccountUser($ref);
		$data = $this->accountService->updateAccountUser($accountUser);
		
		if((!$data->hasError) && (!$this->util->hasValue($accountUser->firstName))){
			$accountUser = $data->model;
			$data->model = $this->accountService->getUsers(array(
				"userID" => $accountUser->userID,
				"accountID" => $accountUser->accountID
			));
		}
		
		
		return $this->createApiView($data);
	}
}
?>
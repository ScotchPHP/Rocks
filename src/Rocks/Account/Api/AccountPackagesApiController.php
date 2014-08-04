<?php
namespace Rocks\Account\Api;

use Rocks\Controllers\RocksApiController as RocksApiController;
use Rocks\Account\Models\Package as Package;

class AccountPackagesApiController extends RocksApiController
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
		$data = $this->accountService->getPackages(array(
			"accountID" => $this->request("accountID", "int"),
			"search" => $this->request("search"),
		));		
		
		return $this->createApiView($data);
	}

/* POST */
	function post()
	{
		return $this->updatePackage($_POST);
	}

/* PUT */
	function put()
	{
		global $_PUT;
		return $this->updatePackage($_PUT);
	}

/* DELETE */
	function delete()
	{
	/*
		global $_DELETE;
		$accountUser = new AccountUser($_DELETE);
		$data = $this->accountService->deletePackage($accountUser);
		
		return $this->createApiView($data);
	*/
	}

/* METHODS */
	private function updatePackage(&$ref)
	{
		$package = new Package($ref);
		$data = $this->accountService->updatePackage($package);
		
		return $this->createApiView($data);
	}
}
?>
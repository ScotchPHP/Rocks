<?php
namespace Rocks\Account\Api;

use Rocks\Controllers\RocksApiController as RocksApiController;
use Rocks\Account\Models\Alert as Alert;

class AccountAlertsApiController extends RocksApiController
{
	/*private static $methodMap = array(
		"get" => array(
			"index" => "index",
		),
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
		$data = $this->accountService->getAlerts(array(
			"alertID" => $this->request("alertID", "int"),
			"accountID" => $this->request("accountID", "int"),
			"search" => $this->request("search"),
			"page" => $this->request("pg"),
			"pageSize" => $this->request("ps"),
		));		
		
		return $this->createApiView($data);
	}
	
/* POST */
	function post()
	{
		return $this->updateAccountAlert($_POST);
	}
	
/* PUT */
	function put()
	{
		global $_PUT;
		return $this->updateAccountAlert($_PUT);
	}

/* DELETE */
	function delete()
	{
		global $_DELETE;
		$alert = new Alert($_DELETE);
		$data = $this->accountService->deleteAlert($alert);
		
		return $this->createApiView($data);
	}

/* METHODS */
	private function updateAccountAlert(&$ref)
	{
		$alert = new Alert($ref);
		$data = $this->accountService->updateAlert($alert);
		
		return $this->createApiView($data);
	}
}
?>
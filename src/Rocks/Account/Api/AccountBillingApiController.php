<?php
namespace Rocks\Account\Api;

use Rocks\Controllers\RocksApiController as RocksApiController;
use Rocks\Account\Models\Account as Account;

class AccountBillingApiController extends RocksApiController
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
		$data = $this->accountService->getBilling(array(
			"accountID" => $this->request("accountID", "int"),
			"search" => $this->request("search"),
		));		
		
		return $this->createApiView($data);
	}

/* POST */
	function post()
	{
		$account = new Account($_POST);
		$data = $this->accountService->updateBilling($account);
		
		return $this->createApiView($data);
	}

}
?>
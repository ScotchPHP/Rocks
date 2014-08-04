<?
namespace Rocks\Account\Api;

use Rocks\Controllers\RocksApiController as RocksApiController;
use Rocks\Account\Models\Account as Account;

Class AccountsApiController extends RocksApiController
{
	private static $methodMap = array(
		"get" => array(
			"index" => "index",
			"typeahead" => "typeahead",
		),
		"post" => array(
			"index" => "update",
			"status" => "updateStatus",
		)
	);
	
	const DEFAULT_PAGE_SIZE = 10;
	const POST_SET_STATUS_ACTION = "status";
	
	private $accountService;
	
	function __construct($router = null)
	{
		parent::__construct($router);
		$this->accountService = $this->getService("IAccountService", $this->sqlSession);
	}

/* GET */
	function get()
	{
		return $this->executeMethod(self::$methodMap["get"]);
	}

/* PUT */
	function put()
	{
		global $_PUT;
		return $this->updateInvoiceCharge($_PUT);
	}
	
/* POST */
	function post()
	{
		return $this->executeMethod(self::$methodMap["post"]);
	}

/* METHODS */
	protected function index()
	{
		$data = $this->accountService->getAccounts(array(
			"accountID" => $this->request("accountID", "int"),
			"search" => $this->request("search"),
			"page" => $this->request("pg","int",1),
			"pageSize" => $this->request("ps","int",self::DEFAULT_PAGE_SIZE),
		));
		
		return $this->createApiView($data);
	}
	
	protected function typeahead()
	{
		$data = $this->accountService->getAccountTypeahead(array(
			"search" => $this->request("search"),
		));
		
		return $this->createApiView($data);
	}
	
	protected function update()
	{
		return $this->updateAccount($_POST);
	}
	
	protected function updateAccount(&$ref)
	{
		$account = new Account($ref);
		$data = $this->accountService->updateAccount($account);
		
		return $this->createApiView($data);
	}
	
	protected function updateStatus()
	{
		$account = new Account($_POST);
		$data = $this->accountService->updateAccountSetActive($account);
		
		return $this->createApiView($data);
	}
}
?>
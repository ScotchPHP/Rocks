<?
namespace Rocks\Account\Api;

use Rocks\Controllers\RocksApiController as RocksApiController;
use Rocks\Account\Models\User as User;

Class UsersApiController extends RocksApiController
{
	private static $methodMap = array(
		"get" => array(
			"index" => "index",
			"typeahead" => "typeahead",
		)
	);
	
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
	
/* METHODS */
	function index()
	{
		$data = $this->accountService->getUsers(array(
			"userID" => $this->request("userID", "int"),
			"search" =>  $this->request("search"),
		));
		
		return $this->createApiView($data);
	}
	
	function typeahead()
	{
		$data = $this->accountService->getUserTypeahead(array(
			"search" => $this->request("search"),
		));
		
		return $this->createApiView($data);
	}
	
}
?>
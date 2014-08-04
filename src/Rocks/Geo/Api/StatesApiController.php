<?php
namespace Rocks\Geo\Api;

use Rocks\Controllers\RocksApiController as RocksApiController;

Class StatesApiController extends RocksApiController
{
	/*private static $methodMap = array(
		"get" => array(
			"index" => "index",
		),
	);*/
	
	private $geoService;
	
	function __construct($router = null)
	{
		parent::__construct($router);
		$this->geoService = $this->getService("IGeoService", $this->sqlSession);
	}
	
/* GET */
	function get()
	{
		$data = $this->geoService->getStates(array(
			"countryID" => $this->request("countryID","string",null,$_GET),
			"stateID" => $this->request("stateID","string",null,$_GET),
		));
		
		return $this->createApiView($data);
	}
	
}
?>
<?php
namespace Rocks\Geo\Api;

use Rocks\Controllers\RocksApiController as RocksApiController;

Class CountriesApiController extends RocksApiController
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
		$data = $this->geoService->getCountries(array(
			"countryID" => $this->requestString("countryID",null,$_GET),
			"acceptCC" => $this->requestBit("acceptCC",null,$_GET),
		));
		
		return $this->createApiView($data);
	}
	
}
?>
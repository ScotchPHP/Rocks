<?php
namespace Rocks\Geo\Api;

use Rocks\Controllers\RocksApiController as RocksApiController;

Class CitiesApiController extends RocksApiController
{
	private static $methodMap = array(
		"get" => array(
			"index" => "index",
			"typeahead" => "typeahead",
		),
		"post" => array(
		)
	);
	
	const DEFAULT_PAGE_SIZE = 20;
	
	private $geoService;
	
	function __construct($router = null)
	{
		parent::__construct($router);
		$this->geoService = $this->getService("IGeoService", $this->sqlSession);
	}
	
/* GET */
	function get()
	{
		return $this->executeMethod(self::$methodMap["get"]);
	}

/* METHODS */
	protected function index()
	{
		$data = $this->geoService->getCities(array(
			"countryID" => $this->request("countryID"),
			"stateID" => $this->request("stateID"),
			"search" => $this->request("search"),
			"page" => $this->request("pg","int",1),
			"pageSize" => $this->request("ps","int",self::DEFAULT_PAGE_SIZE),
		));
		
		return $this->createApiView($data);
	}
	
	protected function typeahead()
	{
		$data = $this->geoService->getCitiesTypeahead(array(
			"countryID" => $this->request("countryID"),
			"stateID" => $this->request("stateID"),
			"search" => $this->request("search"),
		));
		
		return $this->createApiView($data);
	}

}
?>
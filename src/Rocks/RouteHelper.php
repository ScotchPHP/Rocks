<?
namespace Rocks;

class RouteHelper
{
	private static $singletonInstance;
	
	private function __construct()
	{
	}
	
	public static function getInstance()
	{
		if(!self::$singletonInstance)
		{
			self::$singletonInstance = new RouteHelper();
		}
		
		return self::$singletonInstance;
	}
	
	
	public function getUrlFor($controller, $action, $queryString = null)
	{
		if(
		return "/" . $controller . "/" . $action . "/";
	}
	
	public function getUrlFor($application, $controller, $action, $queryString = null)
	{
		
		return "/" . $application . "/" . $controller . "/" . $action . "/";
	}
}
?>
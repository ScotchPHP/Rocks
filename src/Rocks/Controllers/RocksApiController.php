<?
namespace Rocks\Controllers;

use Scotch\Controllers\IApiController as IApiController;
use Scotch\Exceptions\NotImplementedException as NotImplementedException;
use Scotch\Exceptions\MethodNotFoundException as MethodNotFoundException;

use Rocks\Controllers\RocksController as RocksController;
use Rocks\Models\ApiOutput as ApiOutput;
use Rocks\AuthenticationInfo as AuthenticationInfo;

abstract class RocksApiController extends RocksController implements IApiController
{
	function __construct($router = null)
	{
		parent::__construct($router);
	}
	
	public function throwServerError()
	{
		header("HTTP/1.1 500 Internal Server Error");
	}
	
	public function get()
	{
		throw new NotImplementedException("GET is not implemented for this Api Controller object");
	}
	
	public function post()
	{
		throw new NotImplementedException("POST is not implemented for this Api Controller object");
	}
	
	public function put()
	{
		throw new NotImplementedException("PUT is not implemented for this Api Controller object");	
	}
	
	public function delete()
	{
		throw new NotImplementedException("DELETE is not implemented for this Api Controller object");
	}
	
	public function executeMethod($methodMap = array())
	{
		$action = $this->util->getValue($methodMap, $this->requestString("action", "index"));
		if ( !method_exists($this, $action) )
		{
			throw new MethodNotFoundException("$action method not found in this Api Controller object");
		}
		else
		{
			return $this->$action();
		}
	}
	
	protected function getMethodData(&$inputs)
	{
	
		// TODO : Look into this to see if this is a security issue.
		return parse_str(file_get_contents("php://input"),$inputs);
	}
	
	protected function getLoginUrl()
	{
		return null;
	}
}
?>
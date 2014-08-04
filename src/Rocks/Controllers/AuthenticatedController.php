<?php
namespace Rocks\Controllers;

use Rocks\Controllers\RocksController as RocksController;

/*
*  Controller to require that the session is a currently logged in user
*/
class AuthenticatedController extends RocksController
{
	function __construct($router = null)
	{
		parent::__construct($router);
		
		$this->ensureAuthenticatedUser($this->urlFor("login","","redirectTo=".urlencode($this->requestUri)));
	}
}
?>
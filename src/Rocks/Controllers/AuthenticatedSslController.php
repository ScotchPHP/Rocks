<?php
namespace Rocks\Controllers;

use Rocks\Controllers\AuthenticatedController as AuthenticatedController;

/*
*  Controller that forces the user to be authenticated and the current connection to be over SSL.
*/
class AuthenticatedSslController extends AuthenticatedController
{
	function __construct($router = null)
	{
		parent::__construct($router);
		
		$this->ensureHttps();
	}
}
?>
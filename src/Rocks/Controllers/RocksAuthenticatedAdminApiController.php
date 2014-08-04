<?php
namespace Rocks\Controllers;

use Rocks\Controllers\RocksAuthenticatedApiController as RocksAuthenticatedApiController;

class RocksAuthenticatedAdminApiController extends RocksAuthenticatedApiController
{
	function __construct($router = null)
	{
		parent::__construct($router);
		if( !$this->isAdminAuthenticated() )
		{
			throw new NotAuthenticatedException("500 - Admin Not Authenticated");
		}
	}
}
?> 
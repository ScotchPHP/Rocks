<?php
namespace Rocks\Controllers;

use Rocks\Controllers\RocksAuthenticatedApiController as RocksAuthenticatedApiController;

class RocksAuthenticatedAccountApiController extends RocksAuthenticatedApiController
{
	function __construct($router = null)
	{
		parent::__construct($router);
		if( !$this->isAccountAuthenticated() )
		{
			throw new NotAuthenticatedException("500 - Account Not Authenticated");
		}
	}
}
?> 
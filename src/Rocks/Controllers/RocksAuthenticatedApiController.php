<?php
namespace Rocks\Controllers;

use Rocks\Controllers\RocksApiController as RocksApiController;
use Scotch\Exceptions\NotAuthenticatedException as NotAuthenticatedException;

class RocksAuthenticatedApiController extends RocksApiController
{
	function __construct($router = null)
	{
		parent::__construct($router);
		if( !$this->isUserAuthenticated() )
		{
			throw new NotAuthenticatedException("500 - User Not Authenticated");
		}
	}
}
?> 